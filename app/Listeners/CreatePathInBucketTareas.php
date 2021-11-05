<?php

namespace App\Listeners;

use App\Events\MoveMontajes;
use App\Models\BackpackUser;
use App\Models\Installer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Storage;

class CreatePathInBucketTareas
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        Storage::makeDirectory('tareas/tarea_' . $event->tarea['id'].'/montajes');
    }
}
