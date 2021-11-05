<?php

namespace App\Listeners;

use App\Events\MoveMontajes;
use App\Models\BackpackUser;
use App\Models\Installer;
use App\Models\Tarea;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Storage;
use function GuzzleHttp\Promise\task;

class MoveAndSaveMontaje
{
    public $task;

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
        $task = $this->task = $event->tarea;

        $path = str_replace('montajes', 'tarea_' . $task->id . '/montajes', $task->montaje);
        Storage::move($task->montaje, $path);

        \DB::table('tareas')->where('id', $task->id)->update(['montaje' => $path]);
    }
}
