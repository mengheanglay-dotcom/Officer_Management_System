<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly bool $approved,
        private readonly ?string $reason = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->approved ? 'Officer account approved' : 'Officer registration update')
            ->greeting('Hello '.$notifiable->name)
            ->line($this->approved
                ? 'Your officer account has been approved. You can now log in.'
                : 'Your officer registration was not approved.')
            ->when(! $this->approved && $this->reason, fn (MailMessage $mail) => $mail
                ->line('Reason: '.$this->reason)
                ->line('Please contact an administrator if you need more information.'));
    }
}
