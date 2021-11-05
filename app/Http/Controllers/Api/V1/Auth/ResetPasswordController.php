<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\BaseApiController;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends BaseApiController
{

    use ResetsPasswords;

    protected function sendResetResponse(Request $request, $response)
    {
        return $this->successResponse(["data" => ["message" => $response]], 200);
    }

    protected function sendResetFailedResponse(Request $request, $response)
    {
        return $this->errorResponse(["data" => ["error" => $response]], 422);
    }

}
