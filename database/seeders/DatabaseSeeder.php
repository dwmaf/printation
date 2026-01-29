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
            [
                'filename' => 'files/KP-IF-05-kshnxx3487.docx',
                'original_name' => 'KP-IF-05.docx',
            ],
            [
                'filename' => 'files/KP-IF-06-723ye87239zm.docx',
                'original_name' => 'KP-IF-06.docx',
            ],
            [
                'filename' => 'files/KP-IF-07-dg7xn37xn9.docx',
                'original_name' => 'KP-IF-07.docx',
            ]
        ];

        foreach ($data as $item) {
            Printfile::create($item);
        }
    }
}
