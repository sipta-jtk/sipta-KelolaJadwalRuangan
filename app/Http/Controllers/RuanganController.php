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
// use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
// use Cloudinary\Cloudinary as CloudinarySDK;
// use Cloudinary\Configuration\Configuration;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;


class RuanganController extends Controller
{

    /**
     * Menyimpan foto ke storage lokal
     */
    private function uploadFoto($foto, $fileName)
    {
        try {
            // Convert UUID object to string if needed
            $fileName = (string) $fileName;
            
            \Log::info('Starting file upload to local storage', [
                'file_name' => $fileName,
                'original_name' => $foto->getClientOriginalName(),
                'mime_type' => $foto->getMimeType(),
                'size' => $foto->getSize()
            ]);

            // Generate nama file dengan ekstensi
            $extension = $foto->getClientOriginalExtension();
            $fullFileName = $fileName . '.' . $extension;

            // Simpan file ke storage
            $path = $foto->storeAs('ruangan', $fullFileName, 'public');

            if (!$path) {
                throw new \Exception('Failed to store file in local storage');
            }

            \Log::info('Upload successful:', [
                'file_path' => $path,
                'full_file_name' => $fullFileName
            ]);
            
            return $fullFileName;
            
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
     * Menghapus foto dari storage lokal
     */
    private function deleteFoto($fileName)
    {
        if ($fileName) {
            try {
                $path = 'ruangan/' . $fileName;
                
                \Log::info('Deleting file from local storage:', [
                    'file_path' => $path
                ]);

                // Hapus file dari storage
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                    \Log::info('File successfully deleted from local storage:', [
                        'file_path' => $path
                    ]);
                } else {
                    \Log::warning('File not found in storage:', [
                        'file_path' => $path
                    ]);
                }
            } catch (\Exception $e) {
                \Log::error('Failed to delete file from local storage:', [
                    'file_path' => $path ?? null,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw new \Exception('Failed to delete file from local storage: ' . $e->getMessage());
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

        try {
            $validator = Validator::make($request->all(), [
                'nama_ruangan' => 'required|string|max:127|unique:ruangan,nama_ruangan',
                'kode_ruangan' => 'required|string|max:6|unique:ruangan,kode_ruangan',
                'status_ruangan' => 'required|in:tersedia,tidak_tersedia',
                'kode_gedung' => 'required|exists:gedung,kode_gedung',
                'fasilitas' => 'nullable|array',
                'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($validator->fails()) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'errors' => $validator->errors()
                    ]);
                }
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();
            $fileName = null;
            $ruangan = null;

            // Generate a unique filename for the image
            $imageName = Str::uuid();
            \Log::info('Generated image name', ['image_name' => $imageName]);
            
            // Upload foto ke storage lokal
            $fileName = $this->uploadFoto($request->foto, $imageName);
            \Log::info('File uploaded to local storage', ['file_name' => $fileName]);
            
            // Buat ruangan baru dan simpan ID-nya
            $ruanganData = [
                'nama_ruangan' => $request->nama_ruangan,
                'kode_ruangan' => $request->kode_ruangan,
                'status_ruangan' => $request->status_ruangan,
                'kode_gedung' => $request->kode_gedung,
                'link_ruangan' => $fileName
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

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Ruangan berhasil ditambahkan!',
                    'redirect' => route('ruangan.index')
                ]);
            }

            return redirect()->route('ruangan.index')
                ->with('success', 'Ruangan berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollback();
            
            // Jika upload ke storage lokal berhasil tapi operasi database gagal,
            // hapus file dari storage lokal
            if ($fileName) {
                try {
                    $this->deleteFoto($fileName);
                    \Log::info('Cleaned up local file after failed transaction', [
                        'file_name' => $fileName
                    ]);
                } catch (\Exception $deleteError) {
                    \Log::error('Failed to cleanup local file:', [
                        'file_name' => $fileName,
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
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ]);
            }

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
        $oldFileName = null;
        $newFileName = null;
        $ruangan = null;

        try {
            $ruangan = Ruangan::findOrFail($id);
            $oldFileName = $ruangan->link_ruangan;
            
            // Validasi input
            $validator = Validator::make($request->all(), [
                'kode_ruangan' => [
                    'required',
                    'string',
                    'max:6',
                    Rule::unique('ruangan', 'kode_ruangan')->ignore($id, 'id_ruangan')
                ],
                'nama_ruangan' => [
                    'required',
                    'string',
                    'max:127',
                    Rule::unique('ruangan', 'nama_ruangan')->ignore($id, 'id_ruangan')
                ],
                'status_ruangan' => 'required|in:tersedia,tidak_tersedia',
                'kode_gedung' => 'required|exists:gedung,kode_gedung',
                'fasilitas' => 'required|array|min:1',
                'fasilitas.*' => 'required|integer|min:1',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'remove_foto' => 'nullable|boolean'
            ]);

            if ($validator->fails()) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'errors' => $validator->errors()
                    ]);
                }
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            
            \Log::info('Updating ruangan: ' . $ruangan->nama_ruangan);

            // Handle foto
            if ($request->has('remove_foto') && $request->remove_foto == '1') {
                if ($oldFileName) {
                    $this->deleteFoto($oldFileName);
                    $ruangan->link_ruangan = null;
                    \Log::info('Foto lama dihapus dari local storage');
                }
            } 
            elseif ($request->hasFile('foto')) {
                // Validasi file foto
                if (!$request->file('foto')->isValid()) {
                    throw new \Exception('File foto tidak valid');
                }

                // Upload foto baru ke storage lokal
                $imageName = Str::uuid();
                $newFileName = $this->uploadFoto($request->foto, $imageName);
                
                // Hapus foto lama dari storage lokal jika ada
                if ($oldFileName) {
                    $this->deleteFoto($oldFileName);
                    \Log::info('Foto lama dihapus dari local storage');
                }

                $ruangan->link_ruangan = $newFileName;
                \Log::info('Foto baru diupload ke local storage');
            }
            // Jika tidak ada perubahan foto, biarkan foto lama tetap ada

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

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Ruangan berhasil diperbarui!',
                    'redirect' => route('ruangan.index')
                ]);
            }

            return redirect()->route('ruangan.index')
                ->with('success', 'Ruangan berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollback();
            
            // Jika upload foto baru berhasil tapi operasi database gagal,
            // hapus file baru dari storage lokal
            if ($newFileName) {
                try {
                    $this->deleteFoto($newFileName);
                    \Log::info('Cleaned up new local file after failed transaction', [
                        'file_name' => $newFileName
                    ]);
                } catch (\Exception $deleteError) {
                    \Log::error('Failed to cleanup new local file:', [
                        'file_name' => $newFileName,
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
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ]);
            }

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
        $fileName = null;

        try {
            $ruangan = Ruangan::findOrFail($id);
            $fileName = $ruangan->link_ruangan;
            
            // Check if ruangan is used in penjadwalan
            $isUsedInSchedule = Penjadwalan::where('id_ruangan', $id)->exists();
            if ($isUsedInSchedule) {
                throw new \Exception('Ruangan tidak dapat dihapus karena sedang digunakan dalam penjadwalan.');
            }
            
            // Hapus relasi fasilitas
            $ruangan->fasilitas()->detach();
            
            // Hapus ruangan
            $ruangan->delete();
            
            // Hapus foto dari storage lokal
            if ($fileName) {
                $this->deleteFoto($fileName);
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

    /**
     * Get detail ruangan.
     */
    public function show($id)
    {
        $ruangan = Ruangan::with(['fasilitas', 'gedung'])->findOrFail($id);
        return response()->json($ruangan);
    }
}