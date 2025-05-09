<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('$Super001'),
        ]);
        // Crear permisos
        $Permission1 = Permission::create(['name' => 'ver profesores']);
        $Permission2 = Permission::create(['name' => 'eliminar profesores']);

        // Crear rol y asignar permisos
        // $role = Role::create(['name' => 'admin']);
        // $role->givePermissionTo([$Permission1->name, $name]);

        // Asignar rol a un usuario
        // $user = User::find(1);
        $user->assignRole('admin');
    }
}
