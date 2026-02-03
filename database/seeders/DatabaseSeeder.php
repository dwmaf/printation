<?php

namespace Database\Seeders;

use App\Models\Printfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Outlet;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roleAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $roleOwner = Role::firstOrCreate(['name' => 'outlet-owner']);
        $roleStation = Role::firstOrCreate(['name' => 'station']);
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $data = [
            [
                'filename' => 'files/LAPORAN KERJA PRAKTEK - D1041191045-387n983s7.pdf',
                'original_name' => 'LAPORAN KERJA PRAKTEK - D1041191045.pdf',
            ],
        ];

        foreach ($data as $item) {
            Printfile::create($item);
        }

        $admin = User::create([
            'name' => 'Super Administrator',
            'email' => 'admin@printapp.com',
            'password' => Hash::make('password'), // Password standar
            'email_verified_at' => now(),
        ]);
        $admin->assignRole($roleAdmin);

        $this->command->info('✅ Super Admin created: admin@printapp.com / password');


        // 3. SKENARIO 1: OUTLET "BERKAH JAYA PRINT" (Punya 2 Station)
        $outlet1 = Outlet::create([
            'name' => 'Berkah Jaya Print',
            'address' => 'Jl. Kaliurang KM 5, Yogyakarta',
            'max_stations' => 3, // Boleh punya sampai 3 laptop
            // 'qris_path' => 'qris/sample.jpg' // Aktifkan jika ada file dummy
        ]);

        // 3a. Akun Owner Outlet 1
        $owner1 = User::create([
            'name' => 'Pak Budi (Owner Berkah)',
            'email' => 'budi@berkahjaya.com',
            'password' => Hash::make('password'),
            'outlet_id' => $outlet1->id,
            'email_verified_at' => now(),
        ]);
        $owner1->assignRole($roleOwner);

        // 3b. Station/Laptop 1 (Kasir Depan)
        $station1a = User::create([
            'name' => 'Station Depan - Berkah',
            'email' => 'st1@berkahjaya.com',
            'password' => Hash::make('password'),
            'outlet_id' => $outlet1->id,
            'email_verified_at' => now(),
        ]);
        $station1a->assignRole($roleStation);

        // 3c. Station/Laptop 2 (Ruang Belakang)
        $station1b = User::create([
            'name' => 'Station Belakang - Berkah',
            'email' => 'st2@berkahjaya.com',
            'password' => Hash::make('password'),
            'outlet_id' => $outlet1->id,
            'email_verified_at' => now(),
        ]);
        $station1b->assignRole($roleStation);

        $this->command->info('✅ Outlet 1 Setup Complete: Owner (budi@berkahjaya.com) & 2 Stations');


        // 4. SKENARIO 2: OUTLET "MAHAMERU PHOTOCOPY" (Punya 1 Station)
        $outlet2 = Outlet::create([
            'name' => 'Mahameru Photocopy',
            'address' => 'Jl. Gejayan No 12, Yogyakarta',
            'max_stations' => 1,
        ]);

        // 4a. Akun Owner Outlet 2
        $owner2 = User::create([
            'name' => 'Bu Susi (Owner Mahameru)',
            'email' => 'susi@mahameru.com',
            'password' => Hash::make('password'),
            'outlet_id' => $outlet2->id,
            'email_verified_at' => now(),
        ]);
        $owner2->assignRole($roleOwner);

        // 4b. Station/Laptop 1
        $station2a = User::create([
            'name' => 'Station Utama - Mahameru',
            'email' => 'print@mahameru.com',
            'password' => Hash::make('password'),
            'outlet_id' => $outlet2->id,
            'email_verified_at' => now(),
        ]);
        $station2a->assignRole($roleStation);

        $this->command->info('✅ Outlet 2 Setup Complete: Owner (susi@mahameru.com) & 1 Station');
    }
}
