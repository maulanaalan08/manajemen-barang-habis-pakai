<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'tambah-user']);
        Permission::create(['name' => 'hapus-user']);
        Permission::create(['name' => 'lihat-user']);

        Permission::create(['name' => 'tambah-barang']);
        Permission::create(['name' => 'anggaran-barang']);
        Permission::create(['name' => 'po-barang']);
        Permission::create(['name' => 'penerima-barang']);
        Permission::create(['name' => 'pengeluaran-barang']);
        Permission::create(['name' => 'laporan']);


        Permission::create(['name' => 'anfra-unit']);

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'logistik']);
        Role::create(['name' => 'pengadaan']);
        Role::create(['name' => 'unit']);

        $admin = Role::findByName('admin');
        $admin->givePermissionTo(Permission::all());

        $logistik = Role::findByName('logistik');
        $logistik->givePermissionTo([
            'tambah-barang',
            'anfra-unit',
            'anggaran-barang',
            'penerima-barang',
            'pengeluaran-barang',
            'laporan',
        ]);

        $pengadaan = Role::findByName('pengadaan');
        $pengadaan->givePermissionTo([
            'tambah-barang',
            'anfra-unit',
            'po-barang',
            'laporan',
        ]);

        $unit = Role::findByName('unit');
        $unit->givePermissionTo([
            'anfra-unit',
        ]);
    }
}
