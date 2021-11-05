<?php

namespace App\Traits;

use App\Mail\CampaignEmailNotification;
use App\Mail\TaskEmailNotification;
use App\Models\BackpackUser;
use App\Models\Notifications;
use App\Models\NotificationUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

trait EmailNotification
{
    protected function sendNotification($campaign, $role = null, $subject, $message, $type = 'Campaign')
    {
        $users = BackpackUser::whereHas("roles", function ($q) use ($role) {
            if ($role != null) {
                $q->where("name", $role);
            }
        })->get();

        if (count($users) > 0) {
            $data = [
                'title' => $subject,
                'message' => $message,
                'email_address' => 'admin@publimediacr.com'
            ];

            $notification = Notifications::create($data);

            foreach ($users as $user) {
                sleep(1);
                try {
                    if ($type == 'Campaign') {
                        Mail::to($user->email)->send(new CampaignEmailNotification($campaign, $subject, $message));
                    } else {

                        Mail::to($user->email)->send(new TaskEmailNotification($campaign,$subject,$message));
                    }
                }catch (\Exception $exception){
                    //Log::error($exception->getMessage(),'notification error');
                }

                $data = [
                    'user_id' => $user->id,
                    'notifications_id' => $notification->id,
                    'read_at' => null
                ];
                NotificationUser::create($data);
            }
        }
    }
}
