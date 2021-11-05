<?php

use App\Models\ZonasBuses;
use Illuminate\Database\Seeder;
use Edgcarmu\Crgeodata\database\seeds\GeoDataCrDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $this->call(PermissionsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(IdentificationTypesSeeder::class);
        $this->call(GeoDataCrDatabaseSeeder::class);
        $this->call(ZonaTableSeeder::class);
        $this->call(RutaTableSeeder::class);
        $this->call(CarroceriasTableSeeder::class);
        $this->call(AutobuserosTableSeeder::class);
        $this->call(BusesTableSeeder::class);
        $this->call(TareasTiposTableSeeder::class);
        $this->call(ZonasBusesTableSeeder::class);
        $this->call(ZonaPublicitariasTablaSeeder::class);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

//        $this->call(TagsTableSeeder::class);
    }
}
