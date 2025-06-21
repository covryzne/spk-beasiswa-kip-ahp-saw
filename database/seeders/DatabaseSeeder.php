<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting Database Seeding...');
        $this->command->newLine();

        // Seed Users/Accounts first
        $this->call(UserSeeder::class);
        $this->command->newLine();

        // Seed SPK System Data
        $this->call(SPKDataSeeder::class);
        $this->command->newLine();

        $this->command->info('🎉 Database seeding completed successfully!');
        $this->command->info('📝 You can now:');
        $this->command->info('   • Login as Admin: adminspk@gmail.com / password');
        $this->command->info('   • Login as User: user@gmail.com / password');
        $this->command->info('   • Calculate AHP weights in admin panel');
        $this->command->info('   • Run SAW calculations');
    }
}
