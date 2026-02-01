<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Services\DigitalSignatureService;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 0. Cleanup existing data (Optional but follows "hapus semua")
        // Note: Be careful with foreign keys
        Schema::disableForeignKeyConstraints();
        DB::table('model_has_roles')->delete();
        DB::table('model_has_permissions')->delete();
        DB::table('role_has_permissions')->delete();
        DB::table('users')->delete();
        DB::table('roles')->delete();
        Schema::enableForeignKeyConstraints();

        // 1. Setup Roles
        $roleSuperAdmin = Role::create(['name' => 'super_admin', 'guard_name' => 'web', 'panel_access' => ['admin', 'app']]);
        $roleAdmin = Role::create(['name' => 'admin', 'guard_name' => 'web', 'panel_access' => ['admin', 'app']]);
        $roleKabag = Role::create(['name' => 'kepala_bagian', 'guard_name' => 'web', 'panel_access' => ['app']]);
        $roleKasubbag = Role::create(['name' => 'kepala_sub_bagian', 'guard_name' => 'web', 'panel_access' => ['app']]);
        $roleStaff = Role::create(['name' => 'staff', 'guard_name' => 'web', 'panel_access' => ['app']]);

        $password = Hash::make('password');

        // 2. Seed Super Admin
        $this->createUser('Super Admin', 'superadmin', 'superadmin@mail.com', 'super_admin', $password);
        
        // 3. Seed Admin Role Users
        $this->createUser('Admin', 'admin', 'admin@mail.com', 'admin', $password);
        $this->createUser('Sekretaris', 'sekretaris', 'sekretaris@mail.com', 'admin', $password);

        // 4. Seed Kepala Bagian (4 users)
        for ($i = 1; $i <= 4; $i++) {
            $this->createUser("Kepala Bagian $i", "kabag$i", "kabag$i@mail.com", 'kepala_bagian', $password);
        }

        // 5. Seed Kepala Sub Bagian (4 users)
        for ($i = 1; $i <= 4; $i++) {
            $this->createUser("Kepala Sub Bagian $i", "kasubbag$i", "kasubbag$i@mail.com", 'kepala_sub_bagian', $password);
        }

        // 6. Assign Permissions to Roles
        // Ensure permissions exist in case shield hasn't run yet (but it should)
        $permissions = Permission::all();
        
        if ($permissions->count() > 0) {
            $roleSuperAdmin->syncPermissions($permissions);
            $roleAdmin->syncPermissions($permissions);
            $roleKabag->syncPermissions($permissions);
            $roleKasubbag->syncPermissions($permissions);
            $roleStaff->syncPermissions($permissions);
        }

        // 7. Seed Staff (4 users)
        $staffData = [
            ['name' => 'Staf 1', 'username' => 'staf1', 'email' => 'staff1@mail.com'],
            ['name' => 'Staf 2', 'username' => 'staf2', 'email' => 'staff2@mail.com'],
            ['name' => 'Staff 3', 'username' => 'staf3', 'email' => 'staff3@mail.com'],
            ['name' => 'Staff 4', 'username' => 'staf4', 'email' => 'staff4@mail.com'],
        ];

        foreach ($staffData as $s) {
            $this->createUser($s['name'], $s['username'], $s['email'], 'staff', $password);
        }
    }

    private function createUser($name, $username, $email, $roleName, $password)
    {
        $user = User::create([
            'name' => $name,
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'email_verified_at' => now(),
        ]);
        
        $user->assignRole($roleName);
        
        // Generate Keys specifically for Leaders for Digital Signature
        if (str_contains(strtolower($roleName), 'admin') || str_contains(strtolower($roleName), 'kepala')) {
            DigitalSignatureService::generateKeyPair($user);
        }

        return $user;
    }
}
