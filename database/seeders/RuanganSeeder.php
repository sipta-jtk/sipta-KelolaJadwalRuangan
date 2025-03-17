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
            ['kode_ruangan' => 'D-101', 'nama_ruangan' => 'Ruang Kelas 1'],
            ['kode_ruangan' => 'D-102', 'nama_ruangan' => 'Lab Multi Media'],
            ['kode_ruangan' => 'D-104', 'nama_ruangan' => 'Lab Database'],
            ['kode_ruangan' => 'D-217', 'nama_ruangan' => 'Ruang Serba Guna'],
            ['kode_ruangan' => 'D-225', 'nama_ruangan' => 'Ruang Rapat'],
        ];

        foreach ($ruanganData as $ruangan) {
            DB::table('ruangan')->insert([
                'kode_ruangan' => $ruangan['kode_ruangan'],
                'nama_ruangan' => $ruangan['nama_ruangan'],
                'status_ruangan' => $faker->randomElement(['tersedia', 'tidak_tersedia']),
                'kode_gedung' => $faker->randomElement($kodeGedungValues),
                'link_ruangan' => Str::uuid() . '.jpg',
            ]);
        }
    }
}