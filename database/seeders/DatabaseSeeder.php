<?php

namespace Database\Seeders;

use App\Models\Admin;
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
        // User::factory(10)->create();

        Admin::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
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

        $this->call([
            ProductSeeder::class,
        ]);
    }


}
