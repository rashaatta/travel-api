<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TravelRequest;
use App\Http\Resources\TravelResource;
use App\Models\Travel;

/**
 * @group Admin endpoints
 */
class TravelController extends Controller
{
    /**
     * POST Travel
     * Create a new Travel record
     *
     * @authenticated
     *
     * @bodyParam is_public boolean
     * @bodyParam name string required unique
     * @bodyParam description string required
     * @description number_of_days int required
     *
     * @response {"data":{"id":"9cd3b635-978f-43d5-b526-dd800bbdddec","name":"My travel","slug":"my-travel","description":"the second best journy","number_of_days":"4","number_of_nights":3}}
     * @response {"message":"The name has already been taken.","errors":{"name":["The name has already been taken."]}}
     */
    public function store(TravelRequest $request)
    {
        $travel = Travel::create($request->validated());

        return new TravelResource($travel);
    }

    public function update(Travel $travel, TravelRequest $request)
    {
        $travel->update($request->validated());

        return new TravelResource($travel);
    }
}
