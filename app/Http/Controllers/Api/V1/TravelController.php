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
        $deviceToken = 'fjtdT5WnUHJXVHffBWSW2h:APA91bEAIQc8Cp0quW8B69QvDjzE6n8dCqd7cW_raccZP0t_oN84ie1fWzNsl2T-rkKBPZBdrcKR_i5UvyZjYn4yPb0hntAjy1M-zmPtzHiYo1PGfTn6esubdo3-wXpWWriLxtLhsNWW';
        $title = 'Hello Rasha';
        $body = 'Hello Body';

        // Dispatch the job to the queue
        SendPushNotification::dispatch($deviceToken, $title, $body);
        return response()->json(['message' => 'Notification has been queued']);
    }
}
