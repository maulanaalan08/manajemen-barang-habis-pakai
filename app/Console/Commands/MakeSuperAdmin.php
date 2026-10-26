<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class MakeSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:superadmin {username? : Username yang ingin dijadikan Super Admin, atau kosongkan untuk jadikan semua user sebagai Super Admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menjadikan user tertentu atau semua user memiliki role admin (Super Admin) dengan hak akses penuh';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Pastikan role admin ada dan punya semua permission
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $allPermissions = Permission::all();
        if ($allPermissions->count() > 0) {
            $roleAdmin->syncPermissions($allPermissions);
        }

        $username = $this->argument('username');

        if ($username) {
            $user = User::where('username', $username)->first();
            if (!$user) {
                $name = $this->ask('User tidak ditemukan. Ingin buat baru? Masukkan nama', 'Administrator');
                $password = $this->secret('Masukkan password untuk user baru (default: admin)') ?: 'admin';

                $user = User::create([
                    'name' => $name,
                    'username' => $username,
                    'password' => Hash::make($password),
                ]);
                $this->info("User baru '{$username}' berhasil dibuat.");
            }

            $user->assignRole('admin');
            $this->info("Berhasil! User '{$user->username}' sekarang memiliki akses penuh sebagai Super Admin.");
        } else {
            $users = User::all();
            if ($users->isEmpty()) {
                $admin = User::firstOrCreate(
                    ['username' => 'admin'],
                    [
                        'name' => 'Super Administrator',
                        'password' => Hash::make('admin'),
                    ]
                );
                $admin->assignRole('admin');
                $this->info("Database kosong. User default 'admin' (password: admin) berhasil dibuat sebagai Super Admin.");
            } else {
                foreach ($users as $user) {
                    $user->assignRole('admin');
                }
                $this->info("Berhasil! Seluruh user ({$users->count()} user) di database kini memiliki akses penuh sebagai Super Admin.");
            }
        }

        return 0;
    }
}
