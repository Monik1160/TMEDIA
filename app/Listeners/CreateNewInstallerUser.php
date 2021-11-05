<?php

namespace App\Listeners;

use App\Models\BackpackUser;
use App\Models\Installer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateNewInstallerUser
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
        $data = [
            'email' => $event->user['email'],
            'first_name' => $event->user['name'],
            'password' => bcrypt('123'),
            'contact_id' => $event->user['id']
        ];

        BackpackUser::create($data)->assignRole($event->user['provider_type']);
    }
}
