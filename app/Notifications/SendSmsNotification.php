<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\VonageMessage;

class SendSmsNotification extends Notification
{
    use Queueable;

    protected $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['vonage'];
    }

    public function toVonage($notifiable)
    {
        try {
            return (new VonageMessage())
                ->content($this->message);
        } catch (\Exception $e) {
            return 'Failed to send sms: ' . $e->getMessage();
        }
    }
}
