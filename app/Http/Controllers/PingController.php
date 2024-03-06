<?php

namespace App\Http\Controllers;

use App\DTOs\Geo;
use App\Helpers\GeoHelper;
use App\Models\Ping;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class PingController extends Controller
{
    public function store(Request $request)
    {
        // Reject any pings that haven't moved since the last one
        $lastPing = Ping::latest()->first();

        $newPing = new Ping();
        $newPing->data = json_encode($request->query());
        $newPing->lat = $request->get('lat');
        $newPing->lon = $request->get('lon');

        if ($request->has('timestamp')) {
            $newPing->captured_at = Carbon::createFromTimestamp($request->get('timestamp'))->toDateTimeString();
        }

        if ($lastPing) {
            $distance = GeoHelper::distance(
                $lastPing->geo,
                new Geo(floatval($newPing->lat), floatval($newPing->lon))
            );

            // No movement, so ignore this ping
            if ($distance < env('MIN_TRAVEL')) {
                return Response::HTTP_ALREADY_REPORTED;
            }

            $newPing->distance_from_last_ping = $distance;
        }

//        Log::error(json_encode([$lastPing, $newPing, $distance]));

        $newPing->save();

        return Response::HTTP_ACCEPTED;
    }
}
