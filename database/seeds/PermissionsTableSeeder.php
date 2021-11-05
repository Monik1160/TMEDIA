<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'advanced.authentication',
        ]);

        Permission::create([
            'name' => 'developer-tools',
        ]);

        Permission::create([
            'name' => 'mobile-app',
        ]);

        Permission::create([
            'name' => 'logistics',
        ]);

        Permission::create([
            'name' => 'design',
        ]);

        Permission::create([
            'name' => 'finance',
        ]);

        Permission::create([
            'name' => 'executive',
        ]);

        Permission::create([
            'name' => 'contabilidad',
        ]);

        Permission::create([
            'name' => 'adsmanager',
        ]);
    }
}
