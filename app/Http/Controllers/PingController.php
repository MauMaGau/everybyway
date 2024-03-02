<?php

namespace App\Http\Controllers;

use App\Models\Ping;
use Illuminate\Http\Request;

class PingController extends Controller
{
    public function create(Request $request)
    {
        $ping = new Ping();
        $ping->data = $request->all();
        $ping->save();
    }
}
