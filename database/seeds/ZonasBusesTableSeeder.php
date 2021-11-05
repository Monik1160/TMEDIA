<?php

use Illuminate\Database\Seeder;

class ZonasBusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\ZonasBuses::create([
            'name' => 'Trasera',
        ]);

        \App\Models\ZonasBuses::create([
            'name' => 'Lateral Derecho',
        ]);

        \App\Models\ZonasBuses::create([
            'name' => 'Lateral Izquierdo',
        ]);

        \App\Models\ZonasBuses::create([
            'name' => 'Interna',
        ]);

    }
}
