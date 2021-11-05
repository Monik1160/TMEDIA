<?php

use JeroenZwart\CsvSeeder\CsvSeeder;

class RutaTableSeeder extends CsvSeeder
{
    public function __construct()
    {

        $this->file = '/database/seeds/csv/rutas.csv';
        $this->tablename = 'rutas';
        $this->aliases = ['Descripcion' => 'name', 'CodigoMopt' => 'cod_mopt', 'CodigoZona' => 'zona_id'];
        $this->delimiter = ',';
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Recommended when importing larger CSVs
        DB::disableQueryLog();
        parent::run();
    }
}
