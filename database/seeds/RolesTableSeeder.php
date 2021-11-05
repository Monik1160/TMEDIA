<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create(['name' => 'developer']);
        $role->givePermissionTo(Permission::all());

        $role = Role::create(['name' => 'administrador']);
        $role->givePermissionTo('advanced.authentication');
        $role->givePermissionTo('adsmanager');

        $role = Role::create(['name' => 'instalador']);
        $role->givePermissionTo('mobile-app');

        $role = Role::create(['name' => 'cliente']);
        $role->givePermissionTo('mobile-app');

        $role = Role::create(['name' => 'ejecutivo']);
        $role->givePermissionTo('executive');
        $role->givePermissionTo('adsmanager');
        $role->givePermissionTo('contabilidad');

        $role = Role::create(['name' => 'financiero']);
        $role->givePermissionTo('finance');
        $role->givePermissionTo('adsmanager');
        $role->givePermissionTo('contabilidad');

        $role = Role::create(['name' => 'logistica']);
        $role->givePermissionTo('logistics');
        $role->givePermissionTo('adsmanager');
        $role->givePermissionTo('contabilidad');

        $role = Role::create(['name' => 'diseño']);
        $role->givePermissionTo('design');
        $role->givePermissionTo('contabilidad');
        $role->givePermissionTo('adsmanager');

    }
}
