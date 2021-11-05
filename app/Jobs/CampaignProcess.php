<?php

namespace App\Jobs;

use App\Mail\CampaignEmailNotification;
use App\Models\BackpackUser;
use App\Models\Campaing;
use App\Models\Notifications;
use App\Models\NotificationUser;
use App\Traits\EmailNotification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class CampaignProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, EmailNotification;


    protected $process;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($process)
    {
        $this->process = $process;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $campaigns = Campaing::all();

        foreach ($campaigns as $campaign) {
            if ($this->process == 'Renovation') {
                $date = Carbon::parse($campaign->end_date);
                $now = Carbon::now();
                $diff = $date->diffInDays($now);

                if ($diff <= 15) {
                    $subject = "Fecha de Finalización de la campaña {$campaign->name}";
                    $message = "La {$campaign->name} esta por vencerce y podria ser renovada.";

                    $this->sendNotification($campaign, 'Ejecutivo', $subject, $message);
                }

                if ($diff == 0) {
                    TareaEndProcess::dispatch($campaign->task);
                }
            } else {
                $date = Carbon::parse($campaign->start_date);
                $now = Carbon::now();
                $diff = $date->diffInDays($now);
                if ($diff == 0) {
                    //Enviar notificacion al STAFF de la campañana inciada
                    $campaign->status = 7;
                    $campaign->save();
                }

            }
        }
    }
}
