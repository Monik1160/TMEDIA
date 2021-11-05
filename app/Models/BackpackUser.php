<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Notifications\Notifiable;
use Backpack\CRUD\app\Models\Traits\InheritsRelationsFromParentModel;
use Backpack\CRUD\app\Notifications\ResetPasswordNotification as ResetPasswordNotification;

class BackpackUser extends User
{
    use InheritsRelationsFromParentModel;
    use Notifiable;

    protected $table = 'users';

    public static function boot()
    {
        parent::boot();

        self::updated(function ($model) {
            if ($model->userable) {
                $type = get_class($model->userable);

                if ($type == "App\Models\Contact") {
                    $model->userable->email = $model->email;
                    $model->userable->first_name = $model->first_name;
                    $model->userable->last_name = $model->last_name;
                    $model->userable->save();
                }
            }
        });
    }

    /**
     * Send the password reset notification.
     *
     * @param string $token
     *
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * @return MorphTo
     */
    public function userable()
    {
        return $this->morphTo();
    }

    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset()
    {
        return $this->email;
    }
}
