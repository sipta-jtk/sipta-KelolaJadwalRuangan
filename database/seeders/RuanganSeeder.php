<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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

        foreach (range(1, 5) as $index) {
            DB::table('ruangan')->insert([
                'kode_ruangan' => $faker->unique()->numerify('R###'),
                'nama_ruangan' => $faker->unique()->word,
                'status_ruangan' => $faker->randomElement(['tersedia', 'tidak_tersedia']),
                'kode_gedung' => $faker->randomElement($kodeGedungValues),
                'link_ruangan' => substr($faker->url, 0, 45),
            ]);
        }
    }
}