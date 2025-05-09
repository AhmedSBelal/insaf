<?php

namespace App\Notifications\supplier_notifications;

use App\Enums\NotificationType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SupplierAccountDeletedNotification extends Notification
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
            ->subject('Your Supplier Account Has Been Removed')
            ->greeting("Hello {$this->data['name']},")
            ->line("We're writing to inform you that your supplier account has been removed from our system.")
            ->line("**Account Deletion Date:** {$this->data['deletion_date']}")
            ->line("**Reason:** {$this->data['reason']}")
            ->line("If this was a mistake or you have any questions, please contact our support team.")
            ->action('Contact Support', url('/contact'))
            ->line('Thank you for being part of our platform.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => NotificationType::SupplierAccountDeleted->value,
            'message' => 'Your supplier account has been removed',
            'data' => $this->data
        ];
    }
}
