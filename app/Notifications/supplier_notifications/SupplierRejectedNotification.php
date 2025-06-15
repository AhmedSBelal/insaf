<?php

namespace App\Notifications\supplier_notifications;

use App\Enums\NotificationType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SupplierRejectedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public array $data) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
//        return (new MailMessage)
//            ->subject('Your Subject Here')
//            ->markdown('emails.supplier.' . strtolower($this->data['new_status']), [
//                'data' => $this->data
//            ]);
        return (new MailMessage)
            ->subject('Your Supplier Application Status')
            ->greeting("Dear {$this->data['supplier_name']},")
            ->line("We regret to inform you that your supplier application has been rejected.")
            ->line("**Old Status:** {$this->data['old_status']}")
            ->line("**New Status:** {$this->data['new_status']}")
            ->line('**Reason for rejection:**')
            ->line($this->data['reason'] ?? 'Please contact our support team for details.')
            ->action('Review Application Guidelines', url('/supplier/guidelines'))
            ->line('You may reapply after addressing the issues mentioned.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => NotificationType::SupplierRejected->value,
            'message' => 'Your supplier application has been rejected',
            'data' => $this->data
        ];
    }
}
