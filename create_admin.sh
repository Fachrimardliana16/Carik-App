php artisan tinker --execute="
\$user = \App\Models\User::create([
    'name' => 'Super Admin',
    'username' => 'admin',
    'email' => 'admin@sipd.local',
    'password' => bcrypt('password'),
    'email_verified_at' => now(),
]);
\$user->assignRole('super_admin');
echo 'Super Admin created successfully!';
echo PHP_EOL;
echo 'Username: admin';
echo PHP_EOL;
echo 'Password: password';
"
