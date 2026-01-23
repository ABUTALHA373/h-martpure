<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        Admin::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'user_type' => 'system-admin',
            'status' => 'active',
            'password' => bcrypt('password'),
        ]);
        Role::firstOrCreate([
            'name' => 'super-admin',
            'guard_name' => 'admin',
        ]);
        $admin = Admin::first();

        if ($admin) {
            $admin->assignRole('super-admin');
        }
        Admin::factory()->create([
            'name' => 'Test2 User',
            'email' => 'test2@example.com',
            'user_type' => 'admin',
            'status' => 'active',
            'password' => bcrypt('password'),
        ]);

        $this->call([
            ProductSeeder::class,
            PermissionSeeder::class,
            SystemSettingsSeeder::class,
        ]);
    }


}
