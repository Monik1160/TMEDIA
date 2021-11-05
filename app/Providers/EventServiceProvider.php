<?php

namespace App\Providers;

use App\Events\MoveMontajes;
use App\Events\NewUser;
use App\Events\TareasPath;
use App\Events\Task;
use App\Listeners\CreateNewInstallerUser;
use App\Listeners\CreatePathInBucketTareas;
use App\Listeners\CreateTasks;
use App\Listeners\MoveAndSaveMontaje;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        NewUser::class => [
            CreateNewInstallerUser::class,
        ],
        TareasPath::class => [
            CreatePathInBucketTareas::class,
        ],
        MoveMontajes::class => [
            MoveAndSaveMontaje::class,
        ],
        Task::class => [
            CreateTasks::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

    }
}
