<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\BaseApiController;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends BaseApiController {

    use SendsPasswordResetEmails;

    protected function sendResetLinkResponse(Request $request, $response)
    {
        return $this->successResponse(["message" => $response], 200);
    }

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return $this->errorResponse($response, 422);
    }

}
