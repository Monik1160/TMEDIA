<?php

namespace App\Console\Commands;

use App\Jobs\CampaignProcess;
use Illuminate\Console\Command;

class campaign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'campaign:check {action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para disparar los Jobs de Revision de tareas y Campañas';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $params = $this->arguments();

        CampaignProcess::dispatch($params['action']);
    }
}
