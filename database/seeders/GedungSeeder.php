<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class GedungSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $kodeGedungValues = range('A', 'E'); // Limiting to 5 entries

        foreach ($kodeGedungValues as $kodeGedung) {
            DB::table('gedung')->insert([
                'kode_gedung' => $kodeGedung,
                'nama_gedung' => $faker->unique()->word,
            ]);
        }
    }
}