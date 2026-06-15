<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Limpiar la caché de Spatie
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. CREAR PERMISOS ATÓMICOS SIMPLIFICADOS
        
        // Control de Personal y Sistema
        Permission::create(['name' => 'gestionar-usuarios']);     // Crear, editar datos, enviar alertas
        Permission::create(['name' => 'gestionar-contrasenas']);   // Cambiar y resetear contraseñas (Seguridad aislada)
        Permission::create(['name' => 'gestionar-configuracion']); // Parámetros globales del sistema
        
        // Módulo de Asistencias
        Permission::create(['name' => 'ver-asistencias-globales']); // RRHH y Admin ven la asistencia de todos
        Permission::create(['name' => 'ver-propia-asistencia']);    // El trabajador común solo ve su registro
        
        // Módulo de Boletas y Firmas
        Permission::create(['name' => 'gestionar-boletas']);       // Subir boletas de pago al sistema (RRHH / Admin)
        Permission::create(['name' => 'ver-boletas-globales']);    // Auditoría: ver boletas de todo el personal
        Permission::create(['name' => 'ver-firmar-propia-boleta']); // Todos los usuarios ven y firman su boleta
        
        // Módulo de Control Horario (Individual por Usuario)
        Permission::create(['name' => 'programar-horarios']);      // Configurar el horario individual de cada trabajador
        Permission::create(['name' => 'ver-propio-horario']);      // El usuario solo consulta su jornada asignada


        // 3. CREAR ROLES GENERALES Y ASIGNAR PERMISOS

        // Rol: Administrador (Acceso total absoluto)
        $roleAdmin = Role::create(['name' => 'Administrador']);
        $roleAdmin->givePermissionTo(Permission::all());

        // Rol: Recursos Humanos (Enfoque en gestión de personal, asistencias y boletas)
        $roleRRHH = Role::create(['name' => 'Recursos Humanos']);
        $roleRRHH->givePermissionTo([
            'gestionar-usuarios',
            'ver-asistencias-globales',
            'gestionar-boletas',
            'ver-boletas-globales',
            'programar-horarios',
            'ver-propia-asistencia',   // Como trabajador también tiene sus registros
            'ver-firmar-propia-boleta',
            'ver-propio-horario'
        ]);

        // Rol: Usuario (Profesores, Administrativos, Externos - Nivel Operativo Estándar)
        $roleUsuario = Role::create(['name' => 'Usuario']);
        $roleUsuario->givePermissionTo([
            'ver-propia-asistencia',
            'ver-firmar-propia-boleta',
            'ver-propio-horario'
        ]);
    }
}
