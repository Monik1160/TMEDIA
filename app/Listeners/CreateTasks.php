<?php

namespace App\Listeners;

use App\Models\Tarea;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateTasks
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
        $campaign_details = $event->campaign->campaign_details()->get();

        foreach ($campaign_details as $detail){
            Tarea::create([
                'bus_id' => $detail->bus_id,
            ]);
        }

        return 'empanada';
    }
}
