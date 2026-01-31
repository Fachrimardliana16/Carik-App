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
        $roleAdmin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $roleUser = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

        $password = Hash::make('password');

        // 2. Seed Super Admin
        $superAdmin = $this->createUser('Super Admin', 'superadmin', 'superadmin@mail.com', $roleSuperAdmin, $password);
        
        // Super Admin gets all permissions
        $superAdmin->syncPermissions(Permission::all());

        // 3. Seed Admins (admin & sekretaris)
        // Permissions for Admin: All except User, ActivityLog, Role, and CompanySettings
        $adminPermissions = Permission::where('name', 'not like', '%user%')
            ->where('name', 'not like', '%activity%')
            ->where('name', 'not like', '%role%')
            ->where('name', 'not like', '%shield%') // Shield roles
            ->where('name', 'not like', '%setting%') // Company settings
            ->get();
        $roleAdmin->syncPermissions($adminPermissions);

        // admin@mail.com
        $this->createUser('Administrator', 'admin', 'admin@mail.com', $roleAdmin, $password);
        // sekretaris@mail.com
        $this->createUser('Sekretaris', 'sekretaris', 'sekretaris@mail.com', $roleAdmin, $password);

        // 4. Seed Users
        // Users can access all resources but they are scoped by Panel App logic in User.php
        // However, we can also limit their permissions if needed. 
        // For now, let's give them permissions for common resources.
        $userExcluded = ['user', 'activity', 'role', 'shield', 'setting', 'audit'];
        $userPermissions = Permission::query();
        foreach ($userExcluded as $ex) {
            $userPermissions->where('name', 'not like', "%{$ex}%");
        }
        $roleUser->syncPermissions($userPermissions->get());

        $this->createUser('User Satu', 'user1', 'user1@mail.com', $roleUser, $password);
        $this->createUser('User Dua', 'user2', 'user2@mail.com', $roleUser, $password);

        // 5. Scenario Specific Users
        $this->createUser('Direktur Utama', 'direktur', 'direktur@mail.com', $roleUser, $password);
        $this->createUser('Karyawan', 'karyawan', 'karyawan@mail.com', $roleUser, $password);
    }

    private function createUser($name, $username, $email, $role, $password)
    {
        $user = User::updateOrCreate(
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

        return $user;
    }
}
