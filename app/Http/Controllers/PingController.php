<?php

namespace App\Http\Controllers;

use App\DTOs\Geo;
use App\Helpers\GeoHelper;
use App\Models\Ping;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PingController extends Controller
{
    public function store(Request $request)
    {
        // Reject any pings that haven't moved since the last one
        $lastPing = Ping::latest('created_at')->first();

        $newPing = new Ping();
        $newPing->data = json_encode($request->query());
        $newPing->lat = $request->get('lat');
        $newPing->lon = $request->get('lon');
        if ($request->has('timestamp')) {
            $newPing->created_at = Carbon::createFromTimestamp($request->get('timestamp'))->format('Y-m-d H:i:s');
        }

        if ($lastPing && GeoHelper::distance(
            new Geo($lastPing->lat, $lastPing->lon),
            new Geo(floatval($newPing->lat), floatval($newPing->lon))
            ) < env('MIN_TRAVEL')) {
            return Response::HTTP_ALREADY_REPORTED;
        }

        $newPing->save();
    }
}
