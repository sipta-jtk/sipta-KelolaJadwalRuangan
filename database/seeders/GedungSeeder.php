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

        DB::table('gedung')->insert([
            'kode_gedung' => 'D',
            'nama_gedung' => 'Teknik Komputer dan Informatika',
        ]);
    }
}