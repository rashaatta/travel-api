<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TravelResource;
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
}
