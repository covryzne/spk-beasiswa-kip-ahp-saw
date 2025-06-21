<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin SPK',
            'email' => 'adminspk@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Regular User
        User::create([
            'name' => 'User Test',
            'email' => 'user@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        $this->command->info('✅ Users seeded successfully!');
        $this->command->info('   - Admin: adminspk@gmail.com / password');
        $this->command->info('   - User: user@gmail.com / password');
    }
}
