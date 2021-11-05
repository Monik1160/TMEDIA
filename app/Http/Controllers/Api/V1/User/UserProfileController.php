<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\BaseApiController;
use App\Models\Bus;
use Backpack\PermissionManager\app\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserProfileController extends BaseApiController
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
            $this->currentUser = auth('api')->user();

            return $next($request);
        });
    }

    /**
     * To Get Current User Profile.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserProfile()
    {
        $user = \App\Models\BackpackUser::find($this->currentUser->id);
        $role = $user->getRoleNames();
        $role = ($role) ? Role::where('name', $role[0])->pluck('name')->first() : '';

        $tasks = array();

        foreach ($user->task as $task) {
            $bus = Bus::where('id','=',$task->bus_id)->first();
            $task_image = json_decode($task->montaje);
            $tasks[] = [
                'campaign_name' => $task->campaing->name,
                'bus_plate' => $bus->FullPlate,
                'bodywork' => $bus->carroceria->name,
                'campaign_date' => $task->campaing->start_date,
                'bus_company' => $task->campaing->cliente->company_name,
                'notes' => $task->campaing->notes,
                'taskId' => $task->id,
                'task_image' => ($task_image[0]->image) ? \Config::get('filesystems.disks.s3.url').'/'.$task_image[0]->image : asset('images/default_image.png'),
            ];
        }
        $data = [
            'image_profile' => ($user->image_profile) ? \Config::get('filesystems.disks.s3.url').'/'.$user->image_profile : asset('images/default_image.png'),
            'name' => $user->name,
            'total_task' => count($user->task),
            'role' => $role,
            'tasks' => $tasks
        ];

        return $this->successResponse(['data' => $data], 200);
    }

    /**
     * To Update Student User Profile.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateUserProfile(Request $request)
    {

        $this->validate($request, [
            'first_name' => ['min:3'],
            'last_name' => ['min:3'],
            'email' => ['email', Rule::unique('users')->ignore($this->currentUser)],
        ]);

        $this->currentUser->fill($request->only([
            'first_name',
            'last_name',
            'email',
        ]));

        if (!$this->currentUser->isDirty()) {
            return $this->errorResponse(trans('At least one different value must be specified to update'), 422);
        }

        $this->currentUser->save();

        return $this->showOne($this->currentUser);
    }
}
