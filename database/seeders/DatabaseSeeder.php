<?php

namespace Database\Seeders;

use App\Models\Printfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
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
    }
}
