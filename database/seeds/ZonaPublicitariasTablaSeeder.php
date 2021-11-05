<?php

use Illuminate\Database\Seeder;

class ZonaPublicitariasTablaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\ZonaPublicitaria::create([
            'name' => 'Trasera',
            'zonasbuses_id' => 1
        ]);
        \App\Models\ZonaPublicitaria::create([
            'name' => 'Lateral Pequeño',
            'zonasbuses_id' => 2
        ]);
        \App\Models\ZonaPublicitaria::create([
            'name' => 'Lateral Completo',
            'zonasbuses_id' => 3
        ]);
        \App\Models\ZonaPublicitaria::create([
            'name' => 'L (Trasera + Lateral completo)',
            'zonasbuses_id' => 3
        ]);
        \App\Models\ZonaPublicitaria::create([
            'name' => 'Bus Completo',
            'zonasbuses_id' => 5
        ]);
        \App\Models\ZonaPublicitaria::create([
            'name' => 'Asientos pasajeros',
            'zonasbuses_id' => 4
        ]);
        \App\Models\ZonaPublicitaria::create([
            'name' => 'Grafica de Piso',
            'zonasbuses_id' => 4
        ]);
        \App\Models\ZonaPublicitaria::create([
            'name' => 'Precinta Techo',
            'zonasbuses_id' => 4
        ]);
        \App\Models\ZonaPublicitaria::create([
            'name' => 'Agarradera',
            'zonasbuses_id' => 4
        ]);
    }
}
