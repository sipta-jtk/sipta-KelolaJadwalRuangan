<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fasilitas;
use App\Models\RuangFasilitas;
use Illuminate\Support\Facades\Validator;

class FasilitasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Fasilitas::query();

        // Implement search functionality
        if ($request->has('search')) {
            $query->where('nama_fasilitas', 'LIKE', '%' . $request->search . '%');
        }

        $fasilitas = $query->orderBy('nama_fasilitas')->get();
        return view('fasilitas.index', compact('fasilitas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_fasilitas' => 'required|string|max:255|unique:fasilitas,nama_fasilitas',
        ], [
            'nama_fasilitas.required' => 'Nama fasilitas harus diisi.',
            'nama_fasilitas.max' => 'Nama fasilitas maksimal 255 karakter.',
            'nama_fasilitas.unique' => 'Nama fasilitas sudah digunakan.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('fasilitas.index')
                ->withErrors($validator, 'tambahFasilitas')  // Use a named error bag
                ->withInput()
                ->with('showTambahFasilitasModal', true);  // Flag to show modal
        }

        try {
            Fasilitas::create([
                'nama_fasilitas' => $request->nama_fasilitas,
            ]);

            return redirect()->route('fasilitas.index')
                ->with('success', 'Fasilitas berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->route('fasilitas.index')
                ->with('error', 'Terjadi kesalahan saat menambahkan fasilitas.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id_fasilitas)
    {
        $validator = Validator::make($request->all(), [
            'nama_fasilitas' => 'required|string|max:255|unique:fasilitas,nama_fasilitas,' . $id_fasilitas . ',id_fasilitas',
        ], [
            'nama_fasilitas.required' => 'Nama fasilitas harus diisi.',
            'nama_fasilitas.max' => 'Nama fasilitas maksimal 255 karakter.',
            'nama_fasilitas.unique' => 'Nama fasilitas sudah digunakan.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('fasilitas.index')
                ->withErrors($validator, 'editFasilitas_' . $id_fasilitas)  // Use a dynamic named error bag
                ->withInput()
                ->with('showEditFasilitasModal', $id_fasilitas);  // Flag to show specific edit modal
        }

        try {
            $fasilitas = Fasilitas::findOrFail($id_fasilitas);
            $fasilitas->update([
                'nama_fasilitas' => $request->nama_fasilitas,
            ]);

            return redirect()->route('fasilitas.index')
                ->with('success', 'Fasilitas berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('fasilitas.index')
                ->with('error', 'Terjadi kesalahan saat memperbarui fasilitas.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id_fasilitas)
    {
        try {
            $fasilitas = Fasilitas::findOrFail($id_fasilitas);
            
            // Hapus terlebih dahulu data di tabel ruang_fasilitas
            RuangFasilitas::where('id_fasilitas', $id_fasilitas)->delete();

            // Setelah itu baru hapus data fasilitas
            $fasilitas->delete();
            
            return redirect()->route('fasilitas.index')
                ->with('success', 'Fasilitas dan data terkait berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('fasilitas.index')
                ->with('error', 'Terjadi kesalahan saat menghapus fasilitas.');
        }
    }
}
