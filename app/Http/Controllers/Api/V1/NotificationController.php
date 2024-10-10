<?php

namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use App\Services\FirebaseNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function sendNotification(Request $request)
    {
       //$deviceToken = $request->input('device_token');
        $deviceToken = 'cElqkd3ttVtNQzkxUoeXON:APA91bH1zYX36BKSFIGsq7QVKjF49-aY6zmTURlPRKp0gN534_WZw3Kytcr3O5He0xEAbphCXBHbN45zk9kypIZwDkHr08Khl0WDuXqJJA4Ndht3eHuUGt7nABqmRCDVV68bWDsVc3e8';
        $title = $request->input('title');
        $body = $request->input('body');

        $firebaseNotification = new FirebaseNotification();
        $firebaseNotification->sendNotification($deviceToken, $title, $body);
        return response()->json(['message' => 'Notification sent successfully' ]);
        // Dispatch the job to the queue
        //SendPushNotification::dispatch($deviceToken, $title, $body);
    }
}
