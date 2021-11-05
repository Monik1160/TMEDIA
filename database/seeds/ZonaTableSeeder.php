<?php

use JeroenZwart\CsvSeeder\CsvSeeder;

class ZonaTableSeeder extends CsvSeeder
{

    public function __construct()
    {
        $this->file = '/database/seeds/csv/zona.csv';
        $this->tablename = 'zonas';
        $this->aliases = ['Codigo' => 'id', 'Descripcion' => 'description', 'CodProvincia' => 'provincia_id'];
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
