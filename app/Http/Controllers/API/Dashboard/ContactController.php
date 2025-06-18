<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminDashboard\contact\ReplyContactMessageRequest;
use App\Http\Resources\AdminDashboard\contact\ContactMessageCollection;
use App\Http\Resources\AdminDashboard\contact\ContactMessageResource;
use App\Mail\ReplyContactMessage;
use App\Models\Contact;
use App\Traits\ApiResponse;
use \Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse {
        try {
            $contacts = Contact::paginate(16);
            return $this->successResponse(new ContactMessageCollection($contacts), 'Contacts retrieved successfully.');
        } catch (\Exception $exception) {
            Log::error('list of contact message' . $exception->getMessage());
            return $this->failureResponse('unexpected error', 500);
        }
    }

    public function show($id): JsonResponse {
        try {
            $contact = Contact::find($id);
            if (!$contact) {
                return $this->failureResponse('Contact not found', 404);
            }
            return $this->successResponse(new ContactMessageResource($contact), 'Contact retrieved successfully.');
        } catch (\Exception $exception) {
            Log::error('show contact message' . $exception->getMessage());
            return $this->failureResponse('unexpected error', 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $contact = Contact::find($id);
            if (!$contact) {
                return $this->failureResponse('Contact not found', 404);
            }
            $contact->delete();
            return $this->successResponse([], 'Contact deleted successfully.');
        } catch (\Exception $exception) {
            Log::error('delete contact message' . $exception->getMessage());
            return $this->failureResponse('unexpected error', 500);
        }
    }

    public function reply(ReplyContactMessageRequest $request, $id): JsonResponse {
        try {
            $contact = Contact::find($id);
            if (!$contact) {
                return $this->failureResponse('Contact not found', 404);
            }
            $data = $request->validated();

            // send email to the user
            Mail::to($contact->email)->send(new ReplyContactMessage($data));

            return $this->successResponse([], 'Contact reply successfully.');

        } catch (\Exception $exception) {
            Log::error('reply contact message' . $exception->getMessage());
            return $this->failureResponse('unexpected error', 500);
        }
    }

}
