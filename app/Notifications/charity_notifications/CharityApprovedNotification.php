<?php

namespace App\Notifications\charity_notifications;

use App\Enums\NotificationType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CharityApprovedNotification extends Notification
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
            ->subject('Your Charity Account Has Been Approved')
            ->greeting("Congratulations {$this->data['charity_name']}!")
            ->line("We're pleased to inform you that your charity account has been approved.")
            ->line("**Old Status:** {$this->data['old_status']}")
            ->line("**New Status:** {$this->data['new_status']}")
            ->line('You can now start listing your surpluses.')
            ->line('If you have any questions, please contact our support team.');
            // ->action('Discover our surpluses', url('/'))
            // ->line('You can now start listing your products and services.')
            // ->line('If you have any questions, please contact our support team.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => NotificationType::CharityApproved->value,
            'message' => 'Your charity account has been approved',
            'data' => $this->data
        ];
    }
}
