<?php

namespace App\Jobs;

use App\Models\Tarea;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TareaEndProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tasks;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tasks)
    {
        $this->tasks = $tasks;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->tasks as $task) {
            $data = [
                'bus_id' => $task->bus_id,
                'campaing_id' => $task->campaing_id,
                'campaing_detail_id' => $task->campaing_detail_id,
                'art_id' => $task->art_id,
                'notes' => $task->notes,
                'zonapublicitaria_id' => $task->zonapublicitaria_id,
                'montaje' => $task->montaje,
                'tarea_type_id' => 2,
            ];
            Tarea::create($data);
        }
    }
}
