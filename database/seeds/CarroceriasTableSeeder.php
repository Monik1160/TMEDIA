<?php

use JeroenZwart\CsvSeeder\CsvSeeder;

class CarroceriasTableSeeder extends CsvSeeder
{

    public function __construct()
    {
        $this->file = '/database/seeds/csv/carrocerias.csv';
        $this->tablename = 'carrocerias';
        $this->aliases = ['Codigo' => 'id', 'Descripcion' => 'name'];
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
