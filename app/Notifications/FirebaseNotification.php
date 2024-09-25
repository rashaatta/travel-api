<?php

namespace App\Notifications;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FirebaseNotification extends Notification
{
    use Queueable;

    protected $title;
    protected $body;
    protected $deviceToken;

    public function __construct($title, $body, $deviceToken)
    {
        $this->title = $title;
        $this->body = $body;
        $this->deviceToken = $deviceToken;
    }

    public function via()
    {
        return [];
    }
    public function toFirebase()
    {
        // Create a Firebase messaging instance
        $messaging = (new Factory)
            ->withServiceAccount(config('firebase.projects.app.credentials')) // Make sure this points to your Firebase credentials file
            ->createMessaging();

        // Create a CloudMessage
        $message = CloudMessage::withTarget('token', $this->deviceToken)
            ->withNotification([
                'title' => $this->title,
                'body'  => $this->body,
            ]);

        // Send the notification
        $messaging->send($message);
    }
}
