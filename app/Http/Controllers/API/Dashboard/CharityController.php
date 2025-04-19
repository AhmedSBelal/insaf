<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Enums\AdminPermissions;
use App\Enums\CharityStatus;
use App\Enums\SupplierStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminDashboard\charities\CharitySearchRequest;
use App\Http\Requests\AdminDashboard\suppliers\SupplierSearchRequest;
use App\Http\Requests\AdminDashboard\suppliers\SupplierUpdateRequest;
use App\Http\Resources\AdminDashboard\charities\CharityCollection;
use App\Http\Resources\AdminDashboard\charities\CharityResource;
use App\Models\Charity;
use App\Models\User;
use App\Notifications\charity_notifications\CharityAccountDeletedNotification;
use App\Notifications\charity_notifications\CharityApprovedNotification;
use App\Notifications\charity_notifications\CharityRejectedNotification;
use App\Notifications\charity_notifications\CharityPendingNotification;
use App\Services\ImageService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CharityController extends Controller
{
    use ApiResponse;

    public function index(CharitySearchRequest $request) {
        $data = $request->validated();
        $charities = Charity::charitiesSearch($data);
        return $this->successResponse(new CharityCollection($charities), 'Charities retrieved successfully.');
    }

    public function show($charityId) {
        if (!Auth::user()->can(AdminPermissions::ShowCharityDetails->value, 'api')) {
            return $this->failureResponse('Unauthorized access', 403);
        }
        try{
            $charity = Charity::with(['info', 'commercialRegisters'])
                ->where('id', $charityId)
                ->first();
            if (!$charity) {
                return $this->failureResponse('Charity not found', 404);
            }
            return $this->successResponse(new CharityResource($charity), 'Charity retrieved successfully.');
        } catch (\Exception $exception) {
            Log::error("admin show charity >> \n\n" . $exception->getMessage());
            return $this->failureResponse('Something went wrong, try again later');
        }
    }

    public function update(SupplierUpdateRequest $request, $charityId)
    {

        DB::beginTransaction();
        try {
            $data = $request->validated();
            $charity = Charity::find($charityId);
            if (!$charity) {
                return $this->failureResponse('Charity not found', 404);
            }
            if ($charity->status !== $data['status']) {
                $oldStatus = $charity->status;
                $charity->status = $data['status'];
                $charity->save();

                // Send notification
                $this->sendStatusNotification($charity, $oldStatus);
            }
            DB::commit();
            return $this->successResponse(new CharityResource($charity), 'Charity updated successfully.');
        } catch (\Exception $exception) {
            Log::error("admin update charity >> \n\n" . $exception->getMessage());
            return $this->failureResponse('Something went wrong, try again later');
        }
    }

    protected function sendStatusNotification(Charity $charity, string $oldStatus): void
    {
        $user = $charity->info; // Assuming you have user relationship

        $mailData = [
            'old_status' => $oldStatus,
            'new_status' => $charity->status,
            'charity_name' => $user->name,
            'change_date' => now()->format('Y-m-d H:i'),
        ];

        switch ($charity->status) {
            case CharityStatus::Approved->value:
                $user->notify(new CharityApprovedNotification($mailData));
                break;

            case CharityStatus::Rejected->value:
                $user->notify(new CharityRejectedNotification($mailData));
                break;

            case CharityStatus::Pending->value:
                // Only notify if status changed TO pending
                if ($oldStatus !== CharityStatus::Pending->value) {
                    $user->notify(new CharityPendingNotification($mailData));
                }
                break;
        }
    }

    public function destroy($charityId){
        if (!Auth::user()->can(AdminPermissions::DeleteCharity->value, 'api')) {
            return $this->failureResponse('Unauthorized access', 403);
        }
        DB::beginTransaction();
        try{
            $charity = User::with(['charity'])->find($charityId);
            if (!$charity) {
                return $this->failureResponse('Charity not found', 404);
            }
            ImageService::deleteCommercialRegisters($charity->charity);
            $charity->delete();
            $charity->notify(new CharityAccountDeletedNotification([
                'name' => $charity->name,
                'email' => $charity->email,
                'deletion_date' => now()->format('F j, Y H:i'),
                'reason' => request()->input('reason', 'Account cleanup')
            ]));
            DB::commit();
            return $this->successResponse([], 'Charity deleted successfully.');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error("admin delete charity >> \n\n" . $exception->getMessage());
            return $this->failureResponse('Something went wrong, try again later');
        }
    }

}
