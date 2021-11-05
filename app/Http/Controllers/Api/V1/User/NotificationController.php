<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\BaseApiController;
use App\Http\Resources\NotificationResource;
use App\Models\Notifications;
use App\Models\NotificationUser;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class NotificationController extends BaseApiController
{
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

    public function getNotifications(Request $request) {
        Carbon::setWeekStartsAt(Carbon::SUNDAY);
        $users = User::find($this->currentUser->id);
        if ($users) {
            try {
                if ($request->status == "read") {
                    $notifications = $users->notifications()->wherePivot('created_at','>=',Carbon::now()->startOfWeek())->wherePivot('created_at','<=',Carbon::now()->endOfWeek())->wherePivot('read_at', '!=', NULL)->wherePivot('deleted_at', '=', NULL)->get();
                } elseif ($request->status == "archived") {
                    $notifications = $users->notifications()->wherePivot('created_at','>=',Carbon::now()->startOfWeek())->wherePivot('created_at','<=',Carbon::now()->endOfWeek())->wherePivot('deleted_at', '!=', NULL)->get();
                } elseif ($request->status == "unread") {
                    $notifications = $users->notifications()->wherePivot('read_at', '=', NULL)->wherePivot('created_at','>=',Carbon::now()->startOfWeek())->wherePivot('created_at','<=',Carbon::now()->endOfWeek())->wherePivot('deleted_at', '=', NULL)->get();
                } else {
                    $notifications = $users->notifications()->get();
                }
                $allNotification = [];
                foreach ($notifications as $notification) {
                    $allNotification[] = new NotificationResource($notification);
                }
            } catch (Exception $e) {
                DB::rollback();
                return $this->errorResponse($e->getMessage(), 500);
            }
            return $this->successResponse(['data' => $allNotification], 200);
        }
        return $this->errorResponse('User not found.', 500);
    }

    public function readNotification(Request $request) {
        $this->validate($request, [
            'notification_id' => 'required',
        ]);
        $user = User::find($this->currentUser->id);

        if ($user) {
            $notification = Notifications::find($request->notification_id);
            if ($notification) {
                $notificationUser = NotificationUser::where("notifications_id", $request->notification_id)->where("user_id", $user->id)->first();
                $notificationUser->read_at = now();
                $notificationUser->save();
                return $this->successResponse(['data' => [
                    'success' => true
                ]], 200);
            }
            return $this->errorResponse('NotificationResource not found.', 500);
        }
        return $this->errorResponse('User not found.', 500);
    }

    public function unreadNotification(Request $request) {
        $this->validate($request, [
            'notification_id' => ['required'],
            'user_id' => ['required']
        ]);
        $user = User::find($request->user_id);
        if ($user) {
            $notification = Notifications::find($request->notification_id);
            if ($notification) {
                $notificationUser = NotificationUser::where("notification_id", $request->notification_id)->where("user_id", $request->user_id)->first();
                $notificationUser->read_at = NULL;
                $notificationUser->save();
                return $this->successResponse(['data' => [
                    'success' => true
                ]], 200);
            }
            return $this->errorResponse('NotificationResource not found.', 500);
        }
        return $this->errorResponse('User not found.', 500);
    }

    public function archivedNotification(Request $request) {
        $this->validate($request, [
            'notification_id' => ['required']
        ]);
        $user = User::find($this->currentUser->id);
        if ($user) {
            $notification = Notifications::find($request->notification_id);
            if ($notification) {
                // $deleteNotification = NotificationUser::where("notifications_id", $request->notification_id)->where("user_id", $this->currentUser->id)->delete();
                $notification->delete();
                return $this->successResponse(['data' => [
                    'success' => true
                ]], 200);
            }
            return $this->errorResponse('NotificationResource not found.', 500);
        }
        return $this->errorResponse('User not found.', 500);
    }
}
