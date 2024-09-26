<?php

namespace App\Services;

use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Factory;

class FirebaseNotification
{
    protected $messaging;

    public function __construct()
    {
        $credentialsPath = config('firebase.projects.app.credentials');
        if (!file_exists($credentialsPath)) {
            die('Credentials file does not exist.');
        }

        if (!is_readable($credentialsPath)) {
            die('Credentials file is not readable.');
        }
        $firebase = (new Factory)->withServiceAccount($credentialsPath);
        $this->messaging = $firebase->createMessaging();
    }

    public function sendNotification($deviceToken, $title, $body)
    {
        $notification = Notification::create($title, $body);

        $message = CloudMessage::withTarget('token', $deviceToken)
            ->withNotification($notification);

        try {
            $this->messaging->send($message);
            return 'Notification sent successfully';
        } catch (\Exception $e) {
            return 'Failed to send notification: ' . $e->getMessage();
        }
    }
}
