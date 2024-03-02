<?php

namespace App\Http\Controllers;

use App\Models\Ping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PingController extends Controller
{
    public function store(Request $request)
    {
        $ping = new Ping();
        $ping->data = json_encode($request->query());
        $ping->lat = $request->get('lat');
        $ping->lon = $request->get('lon');
        $ping->save();
    }
}
