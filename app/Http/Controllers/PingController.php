<?php

namespace App\Http\Controllers;

use App\Helpers\GeoHelper;
use App\Http\Requests\StorePingRequest;
use App\Models\Ping;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PingController extends Controller
{
    public function store(StorePingRequest $request): JsonResponse
    {
        $user = User::firstOrFail();

        $newPing = new Ping();
        $newPing->user()->associate($user);
        $newPing->data = json_encode($request->query());
        $newPing->lat = $request->get('lat');
        $newPing->lon = $request->get('lon');
        $newPing->captured_at = Carbon::createFromTimestamp($request->get('timestamp'))->toDateTimeString();

        $lastPing = $newPing->previousPing();
        if ($lastPing) {
            $distance = GeoHelper::distance(
                $lastPing->geo,
                $newPing->geo
            );

            // No movement, so ignore this ping
            if ($distance < env('MIN_TRAVEL')) {
                return response()->json([], Response::HTTP_ALREADY_REPORTED);
            }
        }

        $newPing->save();

        return response()->json([], Response::HTTP_ACCEPTED);
    }
}
