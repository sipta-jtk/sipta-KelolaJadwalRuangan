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
            ['kode_ruangan' => 'D-101', 'nama_ruangan' => 'Ruang Kelas 1', 'link_ruangan' => '2ce3c719-eef9-4722-a144-d871f260c9ef.jpeg'],
            ['kode_ruangan' => 'D-102', 'nama_ruangan' => 'Lab Multi Media', 'link_ruangan' => '85349a4a-91ff-4dbb-adf8-2ff07dfc4f74.jpg'],
            ['kode_ruangan' => 'D-104', 'nama_ruangan' => 'Lab Database', 'link_ruangan' => 'db9c0dad-3547-4247-b4bd-4fb3b72817ed.jpeg'],
            ['kode_ruangan' => 'D-217', 'nama_ruangan' => 'Ruang Serba Guna', 'link_ruangan' => '0803a4ee-0255-4374-ac7e-7d45cf383977.jpg'],
            ['kode_ruangan' => 'D-225', 'nama_ruangan' => 'Ruang Rapat', 'link_ruangan' => '7cc2f718-d731-4a28-8d1d-0ba2cc0138ba.jpg'],
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