<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
        ]);
        
        // Display Info
        $this->command->info('✅ Database seeded!');
        $this->command->table(
            ['Role', 'Username', 'Password'],
            [
                ['Super Admin', 'superadmin', 'password'],
                ['Admin', 'admin', 'password'],
                ['User (Dirut)', 'dirut', 'password'],
            ]
        );
    }
}
