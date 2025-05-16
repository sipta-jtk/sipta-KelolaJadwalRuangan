<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use App\Models\Gedung;
use App\Models\Fasilitas;
use App\Models\RuangFasilitas;
use App\Models\Penjadwalan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\FileHelper;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Cloudinary\Cloudinary as CloudinarySDK;
use Cloudinary\Configuration\Configuration;


class RuanganController extends Controller
{

    /**
     * Menyimpan foto dan mengembalikan path relatifnya
     */
    private function uploadFoto($foto, $fileName)
    {
        try {
            // Convert UUID object to string if needed
            $fileName = (string) $fileName;
            
            // Log Cloudinary configuration
            \Log::info('Cloudinary Configuration:', [
                'cloud_name' => config('cloudinary.cloud_name'),
                'api_key' => config('cloudinary.api_key'),
                'api_secret' => config('cloudinary.api_secret'),
                'cloud_url' => config('cloudinary.cloud_url')
            ]);

            \Log::info('Starting file upload to Cloudinary', [
                'file_name' => $fileName,
                'original_name' => $foto->getClientOriginalName(),
                'mime_type' => $foto->getMimeType(),
                'size' => $foto->getSize(),
                'real_path' => $foto->getRealPath()
            ]);

            // Initialize Cloudinary
            Configuration::instance([
                'cloud' => [
                    'cloud_name' => config('cloudinary.cloud_name'),
                    'api_key'    => config('cloudinary.api_key'),
                    'api_secret' => config('cloudinary.api_secret'),
                    'secure'     => true
                ]
            ]);

            // Upload directly to Cloudinary using the SDK
            $cloudinary = new CloudinarySDK();
            $result = $cloudinary->uploadApi()->upload($foto->getRealPath(), [
                'resource_type' => 'image',
                'folder' => env('CLOUDINARY_FOLDER'),
                'public_id' => $fileName,
                'use_filename' => true,
                'unique_filename' => true
            ]);

            \Log::info('Raw Cloudinary response:', ['response' => $result]);

            // Response dari Cloudinary sudah dalam format yang benar
            if (!isset($result['public_id'])) {
                throw new \Exception('Invalid response from Cloudinary: ' . json_encode($result));
            }

            $publicId = $result['public_id'];
            \Log::info('Upload successful:', [
                'cloudinary_id' => $publicId,
                'secure_url' => $result['secure_url'] ?? null
            ]);
            
            return $publicId;
            
        } catch (\Exception $e) {
            \Log::error('Upload failed with error:', [
                'message' => $e->getMessage(),
                'file' => $fileName,
                'trace' => $e->getTraceAsString(),
                'line' => $e->getLine(),
                'file_path' => $e->getFile()
            ]);
            
            throw new \Exception('Failed to upload file: ' . $e->getMessage());
        }
    }


    /**
     * Menghapus foto dari Cloudinary
     */
    private function deleteFoto($publicId)
    {
        if ($publicId) {
            try {
                // Initialize Cloudinary
                Configuration::instance([
                    'cloud' => [
                        'cloud_name' => config('cloudinary.cloud_name'),
                        'api_key'    => config('cloudinary.api_key'),
                        'api_secret' => config('cloudinary.api_secret'),
                        'secure'     => true
                    ]
                ]);

                // Remove file extension from public_id
                // Example: SIPTAJTK/4adb748e-95e1-4435-9482-457591a9f37a.jpeg -> SIPTAJTK/4adb748e-95e1-4435-9482-457591a9f37a
                $publicIdWithoutExt = preg_replace('/\.[^.]*$/', '', $publicId);
                
                \Log::info('Deleting file from Cloudinary:', [
                    'original_public_id' => $publicId,
                    'public_id_without_ext' => $publicIdWithoutExt
                ]);
                
                // Delete using the SDK
                $cloudinary = new CloudinarySDK();
                $result = $cloudinary->uploadApi()->destroy($publicIdWithoutExt, [
                    'resource_type' => 'image'
                ]);

                \Log::info('File successfully deleted from Cloudinary:', [
                    'public_id' => $publicIdWithoutExt,
                    'result' => $result
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to delete file from Cloudinary:', [
                    'public_id' => $publicId,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw new \Exception('Failed to delete file from Cloudinary: ' . $e->getMessage());
            }
        }
    }

    /**
     * Menampilkan daftar ruangan
     */
    public function index(Request $request)
    {
        $query = Ruangan::query();
        
        // Get rooms with their gedung and check if they are used in penjadwalan
        $ruangan = $query->with('gedung')
            ->select('ruangan.*')
            ->leftJoin('penjadwalan', 'ruangan.id_ruangan', '=', 'penjadwalan.id_ruangan')
            ->selectRaw('CASE WHEN COUNT(penjadwalan.id_ruangan) > 0 THEN true ELSE false END as is_used')
            ->groupBy('ruangan.id_ruangan')
            ->get();
        
        return view('ruangan.index', compact('ruangan'));
    }

    /**
     * Menampilkan form untuk membuat ruangan baru
     */
    public function create()
    {
        $gedung = Gedung::all();
        $fasilitas = Fasilitas::all();
        return view('ruangan.create', compact('gedung', 'fasilitas'));
    }

    /**
     * Menyimpan ruangan baru ke database
     */
    public function store(Request $request)
    {
        \Log::info('Starting ruangan store process', [
            'request_data' => $request->except(['foto']),
            'has_file' => $request->hasFile('foto')
        ]);

        $request->validate([
            'nama_ruangan' => 'required|string|max:127|unique:ruangan,nama_ruangan',
            'kode_ruangan' => 'required|string|max:6',
            'status_ruangan' => 'required|in:tersedia,tidak_tersedia',
            'kode_gedung' => 'required|exists:gedung,kode_gedung',
            'fasilitas' => 'nullable|array',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        DB::beginTransaction();
        $cloudinaryId = null;
        $ruangan = null;

        try {
            // Generate a unique filename for the image
            $imageName = Str::uuid();
            \Log::info('Generated image name', ['image_name' => $imageName]);
            
            // Upload foto ke Cloudinary
            $cloudinaryId = $this->uploadFoto($request->foto, $imageName);
            \Log::info('File uploaded to Cloudinary', ['cloudinary_id' => $cloudinaryId]);

            $link_ruangan = $cloudinaryId . '.' . $request->foto->getClientOriginalExtension();
            
            // Buat ruangan baru dan simpan ID-nya
            $ruanganData = [
                'nama_ruangan' => $request->nama_ruangan,
                'kode_ruangan' => $request->kode_ruangan,
                'status_ruangan' => $request->status_ruangan,
                'kode_gedung' => $request->kode_gedung,
                'link_ruangan' => $link_ruangan
            ];

            $ruangan = Ruangan::create($ruanganData);
            \Log::info('Ruangan created', ['ruangan_id' => $ruangan->id_ruangan]);
            
            // Tambahkan fasilitas ke ruangan
            if ($request->has('fasilitas')) {
                foreach ($request->fasilitas as $idFasilitas => $jumlah) {
                    if ($jumlah > 0) {
                        RuangFasilitas::create([
                            'id_ruangan' => $ruangan->id_ruangan,
                            'id_fasilitas' => $idFasilitas,
                            'jumlah_fasilitas' => $jumlah
                        ]);
                        \Log::info('Fasilitas added', [
                            'ruangan_id' => $ruangan->id_ruangan,
                            'fasilitas_id' => $idFasilitas,
                            'jumlah' => $jumlah
                        ]);
                    }
                }
            }
            
            DB::commit();
            \Log::info('Ruangan creation completed successfully');
            return redirect()->route('ruangan.index')
                ->with('success', 'Ruangan berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollback();
            
            // Jika upload ke Cloudinary berhasil tapi operasi database gagal,
            // hapus file dari Cloudinary
            if ($cloudinaryId) {
                try {
                    $this->deleteFoto($cloudinaryId);
                    \Log::info('Cleaned up Cloudinary file after failed transaction', [
                        'cloudinary_id' => $cloudinaryId
                    ]);
                } catch (\Exception $deleteError) {
                    \Log::error('Failed to cleanup Cloudinary file:', [
                        'cloudinary_id' => $cloudinaryId,
                        'error' => $deleteError->getMessage()
                    ]);
                }
            }

            \Log::error('Error in store method:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Menampilkan form untuk mengedit ruangan
     */
    public function edit($id)
    {
        try {
            // Ambil data ruangan beserta relasinya
            // $ruangan = Ruangan::with(['gedung', 'fasilitas'])->findOrFail($id);
            
            $ruangan = Ruangan::findOrFail($id);

            // Ambil data gedung dan fasilitas untuk dropdown
            $gedung = Gedung::all();
            $fasilitas = Fasilitas::all();
            
            return view('ruangan.edit', compact('ruangan', 'gedung', 'fasilitas'));
            
        } catch (\Exception $e) {
            return redirect()->route('ruangan.index')
                ->withErrors(['error' => 'Ruangan tidak ditemukan']);
        }
    }

    /**
     * Mengupdate data ruangan di database
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        $oldCloudinaryId = null;
        $newCloudinaryId = null;
        $ruangan = null;

        try {
            $ruangan = Ruangan::findOrFail($id);
            $oldCloudinaryId = $ruangan->link_ruangan;
            
            // Validasi input
            $request->validate([
                'nama_ruangan' => 'required|string|max:127|unique:ruangan,nama_ruangan,'.$id.',id_ruangan',
                'kode_ruangan' => 'required|string|max:6',
                'status_ruangan' => 'required|in:tersedia,tidak_tersedia',
                'kode_gedung' => 'required|exists:gedung,kode_gedung',
                'fasilitas' => 'nullable|array',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            \Log::info('Updating ruangan: ' . $ruangan->nama_ruangan);

            // Handle foto
            if ($request->remove_foto == '1' && $oldCloudinaryId) {
                $this->deleteFoto($oldCloudinaryId);
                $ruangan->link_ruangan = null;
                \Log::info('Foto lama dihapus dari Cloudinary');
            } 
            elseif ($request->hasFile('foto')) {
                // Upload foto baru ke Cloudinary
                $imageName = Str::uuid();
                $newCloudinaryId = $this->uploadFoto($request->foto, $imageName);
                $link_ruangan = $newCloudinaryId . '.' . $request->foto->getClientOriginalExtension();
                
                // Hapus foto lama dari Cloudinary jika ada
                if ($oldCloudinaryId) {
                    $this->deleteFoto($oldCloudinaryId);
                    \Log::info('Foto lama dihapus dari Cloudinary');
                }

                $ruangan->link_ruangan = $link_ruangan;
                \Log::info('Foto baru diupload ke Cloudinary');
            }

            // Update data ruangan
            $ruangan->update([
                'nama_ruangan' => $request->nama_ruangan,
                'kode_ruangan' => $request->kode_ruangan,
                'status_ruangan' => $request->status_ruangan,
                'kode_gedung' => $request->kode_gedung
            ]);
            \Log::info('Data ruangan diupdate');

            // Update fasilitas
            RuangFasilitas::where('id_ruangan', $ruangan->id_ruangan)->delete();
            \Log::info('Fasilitas lama dihapus');

            if ($request->has('fasilitas')) {
                foreach ($request->fasilitas as $idFasilitas => $jumlah) {
                    if ($jumlah > 0) {
                        RuangFasilitas::create([
                            'id_ruangan' => $ruangan->id_ruangan,
                            'id_fasilitas' => $idFasilitas,
                            'jumlah_fasilitas' => $jumlah
                        ]);
                        \Log::info('Fasilitas ditambahkan: ' . $idFasilitas . ' dengan jumlah ' . $jumlah);
                    }
                }
            }

            DB::commit();
            \Log::info('Update ruangan berhasil');
            return redirect()->route('ruangan.index')
                ->with('success', 'Ruangan berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollback();
            
            // Jika upload foto baru berhasil tapi operasi database gagal,
            // hapus file baru dari Cloudinary
            if ($newCloudinaryId) {
                try {
                    $this->deleteFoto($newCloudinaryId);
                    \Log::info('Cleaned up new Cloudinary file after failed transaction', [
                        'cloudinary_id' => $newCloudinaryId
                    ]);
                } catch (\Exception $deleteError) {
                    \Log::error('Failed to cleanup new Cloudinary file:', [
                        'cloudinary_id' => $newCloudinaryId,
                        'error' => $deleteError->getMessage()
                    ]);
                }
            }

            \Log::error('Error in update method:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Menghapus ruangan dari database
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        $ruangan = null;
        $cloudinaryId = null;

        try {
            $ruangan = Ruangan::findOrFail($id);
            $cloudinaryId = $ruangan->link_ruangan;
            
            // Check if ruangan is used in penjadwalan
            $isUsedInSchedule = Penjadwalan::where('id_ruangan', $id)->exists();
            if ($isUsedInSchedule) {
                throw new \Exception('Ruangan tidak dapat dihapus karena sedang digunakan dalam penjadwalan.');
            }
            
            // Hapus relasi fasilitas
            $ruangan->fasilitas()->detach();
            
            // Hapus ruangan
            $ruangan->delete();
            
            // Hapus foto dari Cloudinary
            if ($cloudinaryId) {
                $this->deleteFoto($cloudinaryId);
            }
            
            DB::commit();
            return redirect()->route('ruangan.index')
                ->with('success', 'Ruangan berhasil dihapus!');
            
        } catch (\Exception $e) {
            DB::rollback();
            
            \Log::error('Error in destroy method:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function namaRuanganTersedia()
    {
        $ruangan = DB::table('ruangan')
            ->select('id_ruangan', 'nama_ruangan')
            ->where('status_ruangan', 'Tersedia')
            ->get();
        return response()->json($ruangan);
    }

    /**
     * Menampilkan ruangan yang tersedia untuk pada tanggal dan sesi tertentu.
     */
    public function ruanganUntukPenjadwalan(Request $request)
    {
        $tanggal = date('Y-m-d', strtotime($request->tanggal));
        $sesi = $request->sesi;

        $availableRooms = DB::table('ruangan')
        ->leftJoin('penjadwalan', function ($join) use ($tanggal, $sesi) {
            $join->on('ruangan.id_ruangan', '=', 'penjadwalan.id_ruangan')
                ->where('penjadwalan.tanggal', '=', $tanggal)
                ->where('penjadwalan.sesi', '=', $sesi);
        })
        ->whereNull('penjadwalan.id_ruangan')
        ->where('ruangan.status_ruangan', '=', 'tersedia')
        ->select('ruangan.id_ruangan', 'ruangan.nama_ruangan')
        ->get();

        return response()->json($availableRooms);
    }

    public function show($id)
    {
        $ruangan = Ruangan::with(['fasilitas', 'gedung'])->findOrFail($id);
        return response()->json($ruangan);
    }
}