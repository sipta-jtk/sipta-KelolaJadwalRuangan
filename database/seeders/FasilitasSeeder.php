<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FasilitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fasilitas = [
            ['nama_fasilitas' => 'Proyektor'],
            ['nama_fasilitas' => 'Whiteboard'],
            ['nama_fasilitas' => 'AC'],
            ['nama_fasilitas' => 'Meja & Kursi'],
            ['nama_fasilitas' => 'Speaker'],
        ];

        DB::table('fasilitas')->insert($fasilitas);
    }
}
