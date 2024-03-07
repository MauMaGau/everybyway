<?php

namespace App\Http\Controllers;

use App\DTOs\Geo;
use App\Helpers\GeoHelper;
use App\Http\Requests\StorePingRequest;
use App\Models\Ping;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PingController extends Controller
{
    public function store(StorePingRequest $request)
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
                $lastPing->previousPing->geo,
                $newPing->geo
            );

            // No movement, so ignore this ping
            if ($distance < env('MIN_TRAVEL')) {
                return Response::HTTP_ALREADY_REPORTED;
            }
        }

        $newPing->save();

        return Response::HTTP_ACCEPTED;
    }
}
