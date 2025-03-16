<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Ruangan;
use App\Models\Fasilitas;

class RuangFasilitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ruanganIds = Ruangan::pluck('id_ruangan')->toArray();
        $fasilitasIds = Fasilitas::pluck('id_fasilitas')->toArray();

        $data = [];

        foreach ($ruanganIds as $ruanganId) {
            $selectedFasilitas = collect($fasilitasIds)->random(rand(2, 5)); // Setiap ruangan punya 2-5 fasilitas

            foreach ($selectedFasilitas as $fasilitasId) {
                $data[] = [
                    'id_ruangan' => $ruanganId,
                    'id_fasilitas' => $fasilitasId,
                    'jumlah_fasilitas' => rand(1, 10),
                ];
            }
        }

        DB::table('ruang_fasilitas')->insert($data);
    }
}
