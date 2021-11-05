<?php

use App\Models\BackpackUser;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = BackpackUser::create([
            'email' => 'fcarvajal@bamboo.cr',
            'first_name' => 'Fabian',
            'last_name' => 'Carvajal',
            'password' => bcrypt('secret'),
        ]);

        $user->assignRole('developer');

        $user = BackpackUser::create([
            'email' => 'jimenachava1@gmail.com',
            'first_name' => 'Jimena',
            'last_name' => 'Chavarría',
            'password' => bcrypt('jimenachava1@123'),
        ]);

        $user->assignRole('administrador');

        //Financiero

        $user = BackpackUser::create([
            'email' => 'lchaves@publimediacr.com',
            'first_name' => 'Leila',
            'last_name' => 'Chaves',
            'password' => bcrypt('lchaves@123'),
        ]);

        $user->assignRole('financiero');

        //Logistica

        $user = BackpackUser::create([
            'email' => 'ocastro@publimediacr.com',
            'first_name' => 'Omar',
            'last_name' => 'Castro',
            'password' => bcrypt('ocastro@123'),
        ]);

        $user->assignRole('logistica');

        //Ejecutivo Ventas

        $user = BackpackUser::create([
            'email' => 'maraya@publimediacr.com',
            'first_name' => 'Mauren',
            'last_name' => 'Araya',
            'password' => bcrypt('maraya@123'),
        ]);

        $user->assignRole('ejecutivo');

        $user = BackpackUser::create([
            'email' => 'ksegura@publimediacr.com',
            'first_name' => 'Kattia',
            'last_name' => 'Segura',
            'password' => bcrypt('ksegura@123'),
        ]);

        $user->assignRole('ejecutivo');

        $user = BackpackUser::create([
            'email' => 'achavarria@publimediacr.com',
            'first_name' => 'Ana Ruth',
            'last_name' => 'Chavarria',
            'password' => bcrypt('achavarria@123'),
        ]);

        $user->assignRole('ejecutivo');

        $user = BackpackUser::create([
            'email' => 'kescalante@publimediacr.com',
            'first_name' => 'Katherine',
            'last_name' => 'Escalante',
            'password' => bcrypt('kescalante@123'),
        ]);

        $user->assignRole('ejecutivo');

        //General

        $user = BackpackUser::create([
            'email' => 'jchavarria@pomp-ar.com',
            'first_name' => 'Jimena',
            'last_name' => 'Chavarria',
            'password' => bcrypt('jchavarria@123'),
        ]);

        $user->assignRole('administrador');

        $user = BackpackUser::create([
            'email' => 'mchavarria@publimediacr.com',
            'first_name' => 'Marcelo',
            'last_name' => 'Chavarria',
            'password' => bcrypt('mchavarria@123'),
        ]);

        $user->assignRole('administrador');

        //Diseño

        $user = BackpackUser::create([
            'email' => 'kgonzalez@publimediacr.com',
            'first_name' => 'Kendal',
            'last_name' => 'Gonzalez',
            'password' => bcrypt('kgonzalez@123'),
        ]);

        $user->assignRole('diseño');
    }
}
