<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\FirebaseNotification;
use Illuminate\Console\Command;

class SendFirebaseNotification extends Command
{
    protected $signature = 'notification:send-firebase';
    protected $description = 'Send Firebase Notifications to users';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $device_token = 'fjtdT5WnUHJXVHffBWSW2h:APA91bEAIQc8Cp0quW8B69QvDjzE6n8dCqd7cW_raccZP0t_oN84ie1fWzNsl2T-rkKBPZBdrcKR_i5UvyZjYn4yPb0hntAjy1M-zmPtzHiYo1PGfTn6esubdo3-wXpWWriLxtLhsNWW';
        $notification = new FirebaseNotification(
            'Hello World! ...',
            'First notification from Rasha Atta',
            $device_token
        );
        // Call the toFirebase() method directly to send the notification
        $notification->toFirebase();
        $this->info('Firebase notifications sent.');
    }
}
