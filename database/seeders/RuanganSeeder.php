<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use App\Models\Gedung;

class RuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Fetch existing kode_gedung values from the gedung table
        $kodeGedungValues = Gedung::pluck('kode_gedung')->toArray();

        // Custom room data with kode_ruangan and nama_ruangan defined manually
        $ruanganData = [
            ['kode_ruangan' => 'D-101', 'nama_ruangan' => 'Ruang Kelas 1', 'link_ruangan' => 'SIPTAJTK/86f94b77-10d2-44e7-a5df-ae1d4c714255.jpg'],
            ['kode_ruangan' => 'D-102', 'nama_ruangan' => 'Lab Multi Media', 'link_ruangan' => 'SIPTAJTK/69e951d3-3d30-4610-8230-f9d07f08fde9.jpg'],
            ['kode_ruangan' => 'D-104', 'nama_ruangan' => 'Lab Database', 'link_ruangan' => 'SIPTAJTK/e18cbc20-8a7a-4f43-b04c-eec31b3f8b23.jpg'],
            ['kode_ruangan' => 'D-217', 'nama_ruangan' => 'Ruang Serba Guna', 'link_ruangan' => 'SIPTAJTK/f0674dcc-d6bc-4b11-a9f8-871061c9e341.jpg'],
            ['kode_ruangan' => 'D-225', 'nama_ruangan' => 'Ruang Rapat', 'link_ruangan' => 'SIPTAJTK/f278d878-a08c-4a21-9cb2-3040da111f58.jpg'],
        ];

        foreach ($ruanganData as $ruangan) {
            DB::table('ruangan')->insert([
                'kode_ruangan' => $ruangan['kode_ruangan'],
                'nama_ruangan' => $ruangan['nama_ruangan'],
                'status_ruangan' => $faker->randomElement(['tersedia', 'tidak_tersedia']),
                'kode_gedung' => $faker->randomElement($kodeGedungValues),
                'link_ruangan' => $ruangan['link_ruangan']
            ]);
        }
    }
}