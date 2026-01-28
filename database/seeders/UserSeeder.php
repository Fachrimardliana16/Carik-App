<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use App\Services\DigitalSignatureService;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Setup Roles
        $roleSuperAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $roleAdmin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']); // Admin & Sekretariat
        $roleUser = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']); // Staff & Heads

        // Retrieve permissions securely
        try {
            $roleSuperAdmin->givePermissionTo(Permission::all());
            // Give Admin & User permissions based on needs (simplified for now to basic access)
            // Realistically, we'd assign viewing permissions here.
        } catch (\Exception $e) {
            // Permissions might not exist yet if fresh
        }

        $password = Hash::make('password');

        // 2. Seed Super Admin
        $this->createUser('Super Admin', 'superadmin', 'superadmin@sipd.local', $roleSuperAdmin, $password);

        // 3. Seed Admins
        $this->createUser('Administrator', 'admin', 'admin@sipd.local', $roleAdmin, $password);
        $this->createUser('Sekretariat', 'sekretariat', 'sekretariat@sipd.local', $roleAdmin, $password);

        // 4. Seed Users (Direksi, Kabag, Unit, dll)
        $users = [
            ['name' => 'Direktur Utama', 'username' => 'dirut'],
            ['name' => 'Direktur Umum', 'username' => 'dirum'],
            ['name' => 'Kepala Bagian Umum', 'username' => 'kabag_umum'],
            ['name' => 'Kepala Bagian Keuangan', 'username' => 'kabag_keuangan'],
            ['name' => 'Kepala Bagian Teknik', 'username' => 'kabag_teknik'],
            ['name' => 'Kepala Bagian Hubungan Langganan', 'username' => 'kabag_hublang'],
            ['name' => 'Kepala Bagian SPI', 'username' => 'kabag_spi'],
            ['name' => 'Kepala Sub Bagian', 'username' => 'kasubag'],
            ['name' => 'Kepala Cabang', 'username' => 'kacab'],
            ['name' => 'Kepala Unit', 'username' => 'kaunit'],
            ['name' => 'Kepala Seksi Teknik', 'username' => 'kasi_teknik'],
            ['name' => 'Kepala Seksi Umum', 'username' => 'kasi_umum'],
            ['name' => 'Koordinator', 'username' => 'koordinator'],
            ['name' => 'Staff', 'username' => 'staff'],
        ];

        foreach ($users as $u) {
            $this->createUser($u['name'], $u['username'], $u['username'] . '@sipd.local', $roleUser, $password);
        }
    }

    private function createUser($name, $username, $email, $role, $password)
    {
        $user = User::firstOrCreate(
            ['username' => $username],
            [
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'email_verified_at' => now(),
            ]
        );
        
        $user->assignRole($role);
        
        // Generate Keys specifically for Directors/Chiefs for convenience
        if (str_contains(strtolower($name), 'direktur') || str_contains(strtolower($name), 'kepala')) {
            DigitalSignatureService::generateKeyPair($user);
        }
    }
}
