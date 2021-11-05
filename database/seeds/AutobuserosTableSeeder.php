<?php

use JeroenZwart\CsvSeeder\CsvSeeder;

class AutobuserosTableSeeder extends CsvSeeder
{
    public function __construct()
    {
        $this->file = '/database/seeds/csv/system_data_publimedia.csv';
        $this->tablename = 'contacts';
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
