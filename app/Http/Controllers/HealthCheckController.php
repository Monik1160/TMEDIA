<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HealthCheckController extends Controller
{
    public function health(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json(['status' => 'ok'], 200);
    }

    public function info( Request $request )
    {
        $info['ms-name'] = env('APP_NAME', 'Anonymous');
        $info['ms-env'] = env('APP_ENV', 'Anonymous');
        $info['ms-debug'] = env('APP_DEBUG', 'Anonymous');
        $info['memory_usage'] = memory_get_usage(true);
        $info['cpu_usage'] = sys_getloadavg()[0];

        return response()->json($info, 200);
    }
}
