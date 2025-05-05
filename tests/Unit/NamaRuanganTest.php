<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Models\Gedung;
use App\Models\Ruangan;
use Illuminate\Support\Str;
use App\Http\Controllers\RuanganController;
use Illuminate\Http\Request;

class NamaRuanganTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Insert gedung first (since ruangan has a foreign key to gedung)
        Gedung::create([
            'kode_gedung' => 'D',
            'nama_gedung' => 'Gedung D'
        ]);

        // Create rooms using the proper model
        Ruangan::create([
            'kode_ruangan' => 'D-101',
            'nama_ruangan' => 'Ruang A',
            'status_ruangan' => 'tersedia',
            'kode_gedung' => 'D',
            'link_ruangan' => Str::uuid() . '.jpg',
        ]);

        Ruangan::create([
            'kode_ruangan' => 'D-102',
            'nama_ruangan' => 'Ruang B',
            'status_ruangan' => 'tersedia',
            'kode_gedung' => 'D',
            'link_ruangan' => Str::uuid() . '.jpg',
        ]);

        Ruangan::create([
            'kode_ruangan' => 'D-104',
            'nama_ruangan' => 'Ruang C',
            'status_ruangan' => 'tidak_tersedia',
            'kode_gedung' => 'D',
            'link_ruangan' => Str::uuid() . '.jpg',
        ]);
    }

    /**
     * Test Nama Ruangan Tersedia
     *
     * @return void
     */
    public function test_nama_ruangan_tersedia()
    {
        // Create request directly to controller instead of using HTTP test
        $request = Request::create('/fake-url', 'GET');
        
        $controller = new RuanganController();
        $response = $controller->namaRuanganTersedia($request);
        
        $data = $response->getData(true);

        // Assert we have 2 available rooms
        $this->assertCount(2, $data);
        
        // Get array of room names
        $roomNames = array_column($data, 'nama_ruangan');
        
        // Assert those rooms are in the result
        $this->assertContains('Ruang A', $roomNames);
        $this->assertContains('Ruang B', $roomNames);
    }

    /**
     * Test Semua Ruangan Tidak Tersedia
     *
     * @return void
     */
    public function test_nama_ruangan_tersedia_returns_empty_when_none_available()
    {
        // First, update all rooms to be 'tidak_tersedia'
        Ruangan::query()->update(['status_ruangan' => 'tidak_tersedia']);
        
        // Create request directly to controller instead of using HTTP test
        $request = Request::create('/fake-url', 'GET');
        
        $controller = new RuanganController();
        $response = $controller->namaRuanganTersedia($request);
        
        $data = $response->getData(true);
        
        // Assert the result is empty
        $this->assertCount(0, $data);
    }
}