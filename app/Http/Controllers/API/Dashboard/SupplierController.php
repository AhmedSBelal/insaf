<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Enums\AdminPermissions;
use App\Enums\ImageType;
use App\Enums\SupplierStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminDashboard\suppliers\SupplierSearchRequest;
use App\Http\Requests\AdminDashboard\suppliers\SupplierUpdateRequest;
use App\Http\Resources\AdminDashboard\suppliers\SupplierCollection;
use App\Http\Resources\AdminDashboard\suppliers\SupplierResource;
use App\Models\Supplier;
use App\Models\User;
use App\Notifications\supplier_notifications\SupplierAccountDeletedNotification;
use App\Notifications\supplier_notifications\SupplierApprovedNotification;
use App\Notifications\supplier_notifications\SupplierPendingNotification;
use App\Notifications\supplier_notifications\SupplierRejectedNotification;
use App\Services\ImageService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SupplierController extends Controller
{
    use ApiResponse;

    public function index(SupplierSearchRequest $request) {
        $data = $request->validated();
        $suppliers = Supplier::suppliersSearch($data);
        return $this->successResponse(new SupplierCollection($suppliers), 'Suppliers retrieved successfully.');
    }

    public function show($id) {
        if (!Auth::user()->can(AdminPermissions::ShowSupplierDetails->value, 'api')) {
            return $this->failureResponse('Unauthorized access', 403);
        }
        try{
            $supplier = Supplier::with(['info', 'commercialRegisters', 'healthCertificates'])
                ->where('supplier_id', $id)
                ->first();
            if (!$supplier) {
                return $this->failureResponse('Supplier not found', 404);
            }
            return $this->successResponse(new SupplierResource($supplier), 'Supplier retrieved successfully.');
        } catch (\Exception $exception) {
            Log::error("admin show supplier >> \n\n" . $exception->getMessage());
            return $this->failureResponse('Something went wrong, try again later');
        }
    }

    public function update($id, SupplierUpdateRequest $request)
    {

        DB::beginTransaction();
        try {
            $data = $request->validated();
            $supplier = Supplier::find($id);
            if (!$supplier) {
                return $this->failureResponse('Supplier not found', 404);
            }
            if ($supplier->status !== $data['status']) {
                $oldStatus = $supplier->status;
                $supplier->status = $data['status'];
                $supplier->save();

                // Send notification
                $this->sendStatusNotification($supplier, $oldStatus);
            }
            DB::commit();
            return $this->successResponse(new SupplierResource($supplier), 'Supplier updated successfully.');
        } catch (\Exception $exception) {
            Log::error("admin update supplier >> \n\n" . $exception->getMessage());
            return $this->failureResponse('Something went wrong, try again later');
        }
    }

    protected function sendStatusNotification(Supplier $supplier, string $oldStatus): void
    {
        $user = $supplier->info; // Assuming you have user relationship

        $mailData = [
            'old_status' => $oldStatus,
            'new_status' => $supplier->status,
            'supplier_name' => $user->name,
            'change_date' => now()->format('Y-m-d H:i'),
        ];

        switch ($supplier->status) {
            case SupplierStatus::Approved->value:
                $user->notify(new SupplierApprovedNotification($mailData));
                break;

            case SupplierStatus::Rejected->value:
                $user->notify(new SupplierRejectedNotification($mailData));
                break;

            case SupplierStatus::Pending->value:
                // Only notify if status changed TO pending
                if ($oldStatus !== SupplierStatus::Pending->value) {
                    $user->notify(new SupplierPendingNotification($mailData));
                }
                break;
        }
    }

    public function destroy($id){
//        dd($id);
        if (!Auth::user()->can(AdminPermissions::DeleteSupplier->value, 'api')) {
            return $this->failureResponse('Unauthorized access', 403);
        }

        DB::beginTransaction();
        try{
            $supplier = User::with(['supplier'])->find($id);
            if (!$supplier) {
                return $this->failureResponse('Supplier not found', 404);
            }

            ImageService::deleteCommercialRegisters($supplier->supplier);
            ImageService::deleteHealthCertificate($supplier->supplier);
            $supplier->delete();
            $supplier->notify(new SupplierAccountDeletedNotification([
                'name' => $supplier->name,
                'email' => $supplier->email,
                'deletion_date' => now()->format('F j, Y H:i'),
                'reason' => request()->input('reason', 'Account cleanup')
            ]));
            DB::commit();
            return $this->successResponse([], 'Supplier deleted successfully.');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error("admin delete supplier >> \n\n" . $exception->getMessage());
            return $this->failureResponse('Something went wrong, try again later');
        }
    }

}
