<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\Ruangan;

class PenjadwalanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Fetch existing id_ruangan values from the ruangan table
        $idRuanganValues = Ruangan::pluck('id_ruangan')->toArray();

        // Define the sessions
        $sessions = [
            1 => ['start' => '07:00:00', 'end' => '09:00:00'],
            2 => ['start' => '09:00:00', 'end' => '11:00:00'],
            3 => ['start' => '13:00:00', 'end' => '15:00:00'],
            4 => ['start' => '15:00:00', 'end' => '17:00:00'],
        ];

        foreach (range(1, 5) as $index) {
            // Randomly select a session
            $sesi = $faker->numberBetween(1, 4);
            $session = $sessions[$sesi];

            // Generate a date for the session
            $date = $faker->dateTimeBetween('now', '+1 week')->format('Y-m-d');

            // Combine the date with the session times
            $start = $date . ' ' . $session['start'];
            $end = $date . ' ' . $session['end'];

            DB::table('penjadwalan')->insert([
                'sesi' => $sesi,
                'agenda' => $faker->randomElement(['seminar_1', 'seminar_2', 'seminar_3', 'sidang']),
                'id_ruangan' => $faker->randomElement($idRuanganValues),
                'tanggal' => $date,
                'id_kota' => $faker->numberBetween(1, 10), // Adjust based on your kota table
                'nip' => $faker->numerify('###########'),
                'start' => $start,
                'end' => $end,
            ]);
        }
    }
}