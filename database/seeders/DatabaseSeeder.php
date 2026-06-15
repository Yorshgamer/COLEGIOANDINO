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

        // 2. Ahora creamos el usuario Administrador Inicial
        $admin = User::create([
            'first_name' => 'Admin',
            'paternal_surname' => 'Colegio',
            'maternal_surname' => 'Andino',
            'dni' => '00000000',
            'email' => 'admin@admin.com',
            'password' => Hash::make('adminadmin'),
            'status' => 'Activo',
        ]);

        // 3. Le asignamos el rol de Administrador que se creó en el paso 1
        $admin->assignRole('Administrador');

        User::factory(30)->create();
    }
}
