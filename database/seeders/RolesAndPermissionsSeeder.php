<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Permisos (ejemplos)
        Permission::firstOrCreate(['name' => 'manage services']);
        Permission::firstOrCreate(['name' => 'manage products']);
        Permission::firstOrCreate(['name' => 'manage appointments']);
        Permission::firstOrCreate(['name' => 'view reports']);
        Permission::firstOrCreate(['name' => 'make sales']);

        // Roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $barber = Role::firstOrCreate(['name' => 'barber']);
        $client = Role::firstOrCreate(['name' => 'client']);

        // Asignar permisos a roles
        $admin->givePermissionTo(Permission::all()); // admin todo
        $barber->givePermissionTo(['manage appointments', 'make sales']);
        // client normalmente no tiene permisos administrativos
    }
}

    
