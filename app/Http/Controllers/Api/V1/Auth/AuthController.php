<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\BaseApiController;
use App\Models\BackpackUser;
use Backpack\PermissionManager\app\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends BaseApiController
{
    /**
     * The logged in user.
     *
     * @var \App\Models\User
     */
    protected $currentUser;

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->currentUser = auth()->guard('api')->user();
            return $next($request);
        });
    }

    public function login(Request $request)
    {
        //Validate data
        $this->validate($request, [
            'username' => ['required'],
            'password' => ['required']
        ]);

        //Validate User
        if (!Auth::validate([config('backpack.base.authentication_column') => $request->get('username'), 'password' => $request->get('password')])) {
            return $this->errorResponse(__('auth.failed'), 401);
        }

        //Validate status and Role
        $user = BackpackUser::where('email', $request->get('username'))->first();

        if (!$user->hasPermissionTo('mobile-app')) {
            return $this->errorResponse(__('auth.platform_failed'), 403);
        }

        $data = [
            'grant_type' => 'password',
            'client_id' => config('passport.PASSWORD_CLIENT_ID'),
            'client_secret' => config('passport.PASSWORD_CLIENT_SECRET'),
            'username' => $request->get('username'),
            'password' => $request->get('password'),
        ];

        $self_request = Request::create('/oauth/token', 'POST', $data);
        $response = app()->handle($self_request);

        // Get the data from the response
        $data = json_decode($response->getContent());

        if (!isset($data->error)) {
            $role = $user->getRoleNames();
            $role = ($role) ? Role::where('name', $role[0])->pluck('name')->first() : '';


            $output = [
                'fullname' => $user->name,
                'image_profile' => ($user->image_profile) ? \Config::get('filesystems.disks.s3.url').'/'.$user->image_profile : asset('images/default_image.png'),
                'token_type' => $data->token_type,
                'expires_in' => $data->expires_in,
                'access_token' => $data->access_token,
                'refresh_token' => $data->refresh_token,
                //'identity_verification' => hash_hmac('sha256', $user->email, env("ONESIGNAL_REST_API_KEY")),
                'role' => $role,
            ];

            return $this->successResponse($output, 200);
        } else {
            return $this->errorResponse(__('auth.invalid_client'), 403);
        }
    }

    public function logout()
    {
        $accessToken = $this->currentUser->token();
        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);


        $accessToken->revoke();

        return $this->successResponse(["data" => ["message" => __("auth.logout")]], 200);
    }
}
