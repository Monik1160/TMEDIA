<?php

use Illuminate\Database\Seeder;

class TareasTiposTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Tareatype::create([
            'name' => 'Instalación',
        ]);

        \App\Models\Tareatype::create([
            'name' => 'Desnstalación',
        ]);

        \App\Models\Tareatype::create([
            'name' => 'Instalación - Desnstalación',
        ]);
    }
}
