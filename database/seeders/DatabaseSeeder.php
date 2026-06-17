<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Primero creamos toda la estructura de Roles y Permisos de Spatie
        $this->call([
            RolesAndPermissionsSeeder::class,
        ]);

        // 2. Ahora creamos usuario
        $admin = User::create([
            'first_name' => 'Admin',
            'paternal_surname' => 'Colegio',
            'maternal_surname' => 'Andino',
            'dni' => '75707785',
            'email' => 'admin@admin.com',
            'password' => Hash::make('adminadmin'),
            'status' => 'Activo',
        ]);

        $yoyo = User::create([
            'first_name' => 'yoyo',
            'paternal_surname' => 'villena',
            'maternal_surname' => 'ochoa',
            'dni' => '12345678',
            'email' => 'asd@gmail.com',
            'password' => Hash::make('asdasdasd'),
            'status' => 'Activo',
        ]);

        $yoyo2 = User::create([
            'first_name' => 'yoyo2',
            'paternal_surname' => 'villena2',
            'maternal_surname' => 'ochoa2',
            'dni' => '99999999',
            'email' => 'asd2@gmail.com',
            'password' => Hash::make('asdasdasd'),
            'status' => 'Activo',
        ]);

        // 3. Le asignamos el rol de Administrador que se creó en el paso 1
        $admin->assignRole('Administrador');
        $yoyo->assignRole('Usuario');
        $yoyo2->assignRole('Usuario');

    }
}
