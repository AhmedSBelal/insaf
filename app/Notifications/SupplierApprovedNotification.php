<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SupplierApprovedNotification extends Notification
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
            ->subject('Your Supplier Account Has Been Approved')
            ->greeting("Congratulations {$this->data['supplier_name']}!")
            ->line("We're pleased to inform you that your supplier account has been approved.")
            ->line("**Old Status:** {$this->data['old_status']}")
            ->line("**New Status:** {$this->data['new_status']}")
            ->action('Access Your Supplier Dashboard', url('/supplier/dashboard'))
            ->line('You can now start listing your products and services.')
            ->line('If you have any questions, please contact our support team.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'supplier_approved',
            'message' => 'Your supplier account has been approved',
            'data' => $this->data
        ];
    }
}
