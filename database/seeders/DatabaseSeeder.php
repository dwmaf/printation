<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Outlet;
use App\Models\Printfile;

use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /**
         * 1 Roles (Spatie)
         */
        $roleAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $roleOwner = Role::firstOrCreate(['name' => 'outlet-owner']);
        $roleStation = Role::firstOrCreate(['name' => 'station']);
        $admin = User::updateOrCreate(
            ['email' => 'admin@upa.printation'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        if (!$admin->hasRole('super-admin')) {
            $admin->assignRole($roleAdmin);
        }

        /**
         * 3 Outlet contoh + Owner + 2 Station
         */
        $outlet1 = Outlet::updateOrCreate(
            ['name' => 'Berkah Jaya Print'],
            [
                'address' => 'Jl. Kaliurang KM 5, Yogyakarta',
                'max_stations' => 3,
            ]
        );

        $owner1 = User::updateOrCreate(
            ['email' => 'budi@berkahjaya.com'],
            [
                'name' => 'Pak Budi',
                'password' => Hash::make('password'),
                'outlet_id' => $outlet1->id,
                'email_verified_at' => now(),
            ]
        );
        if (!$owner1->hasRole('outlet-owner')) {
            $owner1->assignRole($roleOwner);
        }

        $station1a = User::updateOrCreate(
            ['email' => 'st1@berkahjaya.com'],
            [
                'name' => 'Station Depan - Berkah',
                'password' => Hash::make('password'),
                'outlet_id' => $outlet1->id,
                'email_verified_at' => now(),
            ]
        );
        if (!$station1a->hasRole('station')) {
            $station1a->assignRole($roleStation);
        }

        $station1b = User::updateOrCreate(
            ['email' => 'st2@berkahjaya.com'],
            [
                'name' => 'Station Belakang - Berkah',
                'password' => Hash::make('password'),
                'outlet_id' => $outlet1->id,
                'email_verified_at' => now(),
            ]
        );
        if (!$station1b->hasRole('station')) {
            $station1b->assignRole($roleStation);
        }

        $this->command?->info('✅ Outlet 1: Owner + 2 Station created');

        /**
         * 4 Outlet contoh 2 + Owner + 1 Station
         */
        $outlet2 = Outlet::updateOrCreate(
            ['name' => 'Mahameru Photocopy'],
            [
                'address' => 'Jl. Gejayan No 12, Yogyakarta',
                'max_stations' => 1,
            ]
        );

        $owner2 = User::updateOrCreate(
            ['email' => 'susi@mahameru.com'],
            [
                'name' => 'Bu Susi (Owner Mahameru)',
                'password' => Hash::make('password'),
                'outlet_id' => $outlet2->id,
                'email_verified_at' => now(),
            ]
        );
        if (!$owner2->hasRole('outlet-owner')) {
            $owner2->assignRole($roleOwner);
        }

        $station2a = User::updateOrCreate(
            ['email' => 'print@mahameru.com'],
            [
                'name' => 'Station Utama - Mahameru',
                'password' => Hash::make('password'),
                'outlet_id' => $outlet2->id,
                'email_verified_at' => now(),
            ]
        );
        if (!$station2a->hasRole('station')) {
            $station2a->assignRole($roleStation);
        }

        $this->command?->info('✅ Outlet 2: Owner + 1 Station created');

        /**
         * 5 (Opsional) Dummy Printfile
         * Aman: cuma create kalau belum ada filename itu.
         */
        $dummy = [
            [
                'filename' => 'files/LAPORAN KERJA PRAKTEK - D1041191045-387n983s7.pdf',
                'original_name' => 'LAPORAN KERJA PRAKTEK - D1041191045.pdf',
                // kalau tabel printfiles kamu punya station_id, boleh isi:
                // 'station_id' => $station1a->id,
            ],
        ];

        foreach ($dummy as $item) {
            Printfile::firstOrCreate(
                ['filename' => $item['filename']],
                $item
            );
        }

        $this->command?->info('✅ Seeder done');
    }
}
