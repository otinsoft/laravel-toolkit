<?php

namespace Otinsoft\Toolkit\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class Verification extends Notification
{
    /**
     * The verification token.
     *
     * @var string
     */
    public $token;

    /**
     * Create a notification instance.
     *
     * @param  string $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line(__('You’re receiving this email because you recently created a new :app_name account. ', ['app_name' => config('app.name')]))
            ->action('Verify Email', url(config('app.url').route('verification', $this->token, false)))
            ->line(' If this wasn’t you, please ignore this email.');
    }
}
