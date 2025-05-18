<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Http\Controllers\RuanganController;
use App\Models\Gedung;
use App\Models\Ruangan;
use App\Models\Penjadwalan;


class CekRuanganPenjadwalanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Config::set('app.key', 'base64:'.base64_encode(random_bytes(32)));

        // Insert gedung first (since ruangan has a foreign key to gedung)
        Gedung::create([
            'kode_gedung' => 'D',
            'nama_gedung' => 'Gedung D'
        ]);

        $ruangA = Ruangan::create([
            'kode_ruangan' => 'D-101',
            'nama_ruangan' => 'Ruang A',
            'status_ruangan' => 'tersedia',
            'kode_gedung' => 'D',
            'link_ruangan' => Str::uuid() . '.jpg',
        ]);

        
        $ruangB = Ruangan::create([
            'kode_ruangan' => 'D-102',
            'nama_ruangan' => 'Ruang B',
            'status_ruangan' => 'tersedia',
            'kode_gedung' => 'D',
            'link_ruangan' => Str::uuid() . '.jpg',
        ]);
        
        $ruangC = Ruangan::create([
            'kode_ruangan' => 'D-104',
            'nama_ruangan' => 'Ruang C',
            'status_ruangan' => 'tidak_tersedia',
            'kode_gedung' => 'D',
            'link_ruangan' => Str::uuid() . '.jpg',
        ]);

        // Adjust penjadwalan table to use kode_ruangan instead of id_ruangan if needed
        // DB::table('penjadwalan')->insert([
        //     ['kode_ruangan' => 'D-101', 'tanggal' => '2025-05-04', 'sesi' => 1]
        // ]);


        Penjadwalan::create([
            'agenda' => 'seminar_1',
            'start' => '2025-05-05 07:00:00',
            'end' => '2025-05-05 09:00:00',
            'tanggal' => '2025-05-05',
            'id_ruangan' => $ruangA->getKey(),
            'sesi' => 1,
            'id_kota' => 1,
            'nip' => '1234567890'
        ]);
    }
    public function test_ruangan_yang_tersedia_dan_belum_dipakai()
    {
        $request = Request::create('/fake-url', 'GET', [
            'tanggal' => '2025-05-05',
            'sesi' => 1
        ]);

        $controller = new RuanganController();
        $response = $controller->ruanganUntukPenjadwalan($request);

        $data = $response->getData(true);

        $this->assertCount(1, $data);
        $this->assertEquals('Ruang B', $data[0]['nama_ruangan']);
    }

    public function test_tidak_menampilkan_ruangan_yang_tidak_tersedia()
    {
        $request = Request::create('/fake-url', 'GET', [
            'tanggal' => '2025-05-05',
            'sesi' => 1
        ]);

        $controller = new RuanganController();
        $response = $controller->ruanganUntukPenjadwalan($request);

        $data = $response->getData(true);

        // assert missing Ruang C
        $this->assertCount(1, $data);
        $this->assertNotContains('Ruang C', array_column($data, 'nama_ruangan'));
    }

    public function test_ruangan_yang_tersedia_dan_sudah_dipakai()
    {
        $request = Request::create('/fake-url', 'GET', [
            'tanggal' => '2025-05-05',
            'sesi' => 1
        ]);

        $controller = new RuanganController();
        $response = $controller->ruanganUntukPenjadwalan($request);

        $data = $response->getData(true);

        // assert missing Ruang A
        $this->assertCount(1, $data);
        $this->assertNotContains('Ruang A', array_column($data, 'nama_ruangan'));
    }
}
