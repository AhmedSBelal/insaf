<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SupplierPendingNotification extends Notification
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
        return (new MailMessage)
            ->subject('Supplier Application Under Review')
            ->greeting("Hello {$this->data['supplier_name']},")
            ->line("Your supplier application status has been updated to Pending.")
            ->line("**Previous Status:** {$this->data['old_status']}")
            ->line("**Current Status:** {$this->data['new_status']}")
            ->line('Our team is currently reviewing your application.')
            ->line('Expected review time: 3-5 business days')
//            ->action('Check Application Status', url('/supplier/status'))
            ->line('Thank you for your patience.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'supplier_pending',
            'message' => 'Your supplier application is under review',
            'data' => $this->data
        ];
    }
}
