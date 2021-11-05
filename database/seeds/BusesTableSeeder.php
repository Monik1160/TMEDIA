<?php

use JeroenZwart\CsvSeeder\CsvSeeder;

class BusesTableSeeder extends CsvSeeder
{
    public function __construct()
    {
        $this->file = '/database/seeds/csv/buses.csv';
        $this->aliases = ['id' => 'id', 'tipo_placa' => 'tipo_placa','placa' => 'placa'
            ,'tipo_contrato' => 'tipo_contrato', 'carroceria_id' => 'carroceria_id'
            , 'autobusero_id' => 'autobusero_id', 'activo' => 'activo', 'observaciones' => 'observaciones'];
        $this->tablename = 'buses';
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
