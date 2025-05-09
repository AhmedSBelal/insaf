<?php

namespace App\Http\Controllers\API\APP;

use App\Http\Controllers\Controller;
use App\Http\Requests\APP\ContactRequest;
use App\Mail\NewContactSubmissionMail;
use App\Models\Contact;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{

    use ApiResponse;

    public function store(ContactRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            // Store contact message
            $contact = Contact::create($data);

            // Send email notification (queued)
            Mail::to($data['email'])
                ->send(new NewContactSubmissionMail($data));

            // Optional: Send admin notification
//            Mail::to(config('mail.admin_address'))
//                ->send(new NewContactSubmissionMail($data));

            DB::commit();

            return $this->successResponse(
                ['reference_id' => $contact->id],
                'Your message has been sent successfully.'
            );

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Contact submission failed: ' . $exception->getMessage(), [
                'data' => $request->all(),
                'trace' => $exception->getTraceAsString()
            ]);

            return $this->failureResponse(
                'We encountered an error while processing your message. Please try again later.',
                500
            );
        }
    }
}
