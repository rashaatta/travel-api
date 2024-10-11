<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\SendSmsNotification;
use App\Services\FirebaseNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Vonage\Client;
use Vonage\Client\Credentials\Basic;
use Vonage\Messages\Channel\WhatsApp\WhatsAppText;
use Vonage\SMS\Message\SMS;

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
        return response()->json(['message' => 'Notification sent successfully']);
        // Dispatch the job to the queue
        //SendPushNotification::dispatch($deviceToken, $title, $body);
    }

    public function sms()
    {
        try {
            $user = User::whereEmail('rashaatta83@gmail.com')->first();
            Log::info($user->phone_number);
            Log::info(config('services.vonage.sms_from'));
//            $user->notify(new SendSmsNotification("Hi Rasha, First sms" . now()));

            $basic = new Basic(config('services.vonage.key'), config('services.vonage.secret'));
            $client = new Client($basic);

            $response = $client->sms()->send(
                new  SMS($user->phone_number, config('services.vonage.sms_from'), 'Hi Rasha, First sms' . now())
            );

            $message = $response->current();
            Log::info("Sent message to " . $message->getTo() . ". Balance is now " . $message->getRemainingBalance() . PHP_EOL);

            if ($message->getStatus() == 0) {
                echo "The message was sent successfully\n";
            } else {
                echo "The message failed with status: " . $message->getStatus() . "\n";
            }


            return response()->json(['message' => 'SMS sent successfully']);
        } catch (\Exception $e) {
            return 'Failed to send sms: ' . $e->getMessage();
        }
    }

    public function whatsApp()
    {
        try {
            $whatsAppText = new WhatsAppText(
                '14157386102', // Vonage WhatsApp number
                '+201002945652', // Recipient's WhatsApp number
                'Hi Rasha, this is a WA text from Vonage'
            );
            $basic = new Basic(env('VONAGE_KEY'), env('VONAGE_SECRET'));
            $client = new Client($basic);

            $client->messages()->send($whatsAppText);

            return response()->json(['message' => 'whatsApp SMS sent successfully']);
        } catch (\Exception $e) {
            return 'Failed to send whatsApp sms: ' . $e->getMessage();
        }
    }
}
