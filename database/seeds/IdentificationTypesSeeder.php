<?php

use App\Models\IdentificationType;
use Illuminate\Database\Seeder;

class IdentificationTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        IdentificationType::create([
            'name' => 'Cédula Física',
            'validation_regx' => '/^[1-9]-\d{4}-\d{4}$/'
        ]);

        IdentificationType::create([
            'name' => 'Cédula Jurídica',
            'validation_regx' => ''
        ]);

        IdentificationType::create([
            'name' => 'DIMEX',
            'validation_regx' => ''
        ]);

        IdentificationType::create([
            'name' => 'NITE',
            'validation_regx' => ''
        ]);

        IdentificationType::create([
            'name' => 'Pasaporte Extranjero',
            'validation_regx' => ''
        ]);
    }
}
