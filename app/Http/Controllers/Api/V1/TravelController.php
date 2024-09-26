<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TravelResource;
use App\Jobs\SendPushNotification;
use App\Models\Travel;

/**
 * @group Public endpoints
 *
 * @authenticated
 */
class TravelController extends Controller
{
    /**
     * This endpoint to get public travels
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $travels = Travel::search(request('search'))
            ->where('is_public', true)
            ->paginate();

        return TravelResource::collection($travels);
    }

    public function send()
    {
        $deviceToken = 'cElqkd3ttVtNQzkxUoeXON:APA91bH1zYX36BKSFIGsq7QVKjF49-aY6zmTURlPRKp0gN534_WZw3Kytcr3O5He0xEAbphCXBHbN45zk9kypIZwDkHr08Khl0WDuXqJJA4Ndht3eHuUGt7nABqmRCDVV68bWDsVc3e8';
        $title = 'Hi Rasha!';
        $body = 'Welcome with you !';

        // Dispatch the job to the queue
        SendPushNotification::dispatch($deviceToken, $title, $body);
        return response()->json(['message' => 'Notification has been queued']);
    }
}
