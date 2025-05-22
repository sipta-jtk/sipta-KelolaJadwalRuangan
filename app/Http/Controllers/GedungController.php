<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gedung;
use Illuminate\Support\Facades\Validator;

class GedungController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Gedung::query();

        // Implement search functionality
        if ($request->has('search')) {
            $query->where('nama_gedung', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('kode_gedung', 'LIKE', '%' . $request->search . '%');
        }

        // Eager load ruangan relation untuk menghindari N+1 problem
        $gedung = $query->withCount('ruangan')->orderBy('kode_gedung')->get();
        return view('gedung.index', compact('gedung'));
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
        // First, check for name uniqueness before running the other validations
        $existingName = Gedung::where('nama_gedung', $request->nama_gedung)->exists();
        if ($existingName) {
            return redirect()->route('gedung.index')
                ->withErrors(['nama_gedung' => 'Nama gedung sudah digunakan.'], 'tambahGedung')
                ->withInput()
                ->with('showTambahGedungModal', true);
        }

        $validator = Validator::make($request->all(), [
            'kode_gedung' => 'required|string|size:1|unique:gedung,kode_gedung|alpha',
            'nama_gedung' => 'required|string|max:255',
        ], [
            'kode_gedung.size' => 'Kode gedung harus 1 karakter.',
            'kode_gedung.alpha' => 'Kode gedung harus berupa huruf.',
            'kode_gedung.unique' => 'Kode gedung sudah digunakan.',
            'nama_gedung.unique' => 'Nama gedung sudah digunakan.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('gedung.index')
                ->withErrors($validator, 'tambahGedung')  // Use a named error bag
                ->withInput()
                ->with('showTambahGedungModal', true);  // Flag to show modal
        }

        try {
            Gedung::create([
                'kode_gedung' => strtoupper($request->kode_gedung),
                'nama_gedung' => $request->nama_gedung,
            ]);

            return redirect()->route('gedung.index')
                ->with('success', 'Gedung berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Tambahkan logging untuk melihat error detail
            \Log::error('Error saat menambah gedung: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return redirect()->route('gedung.index')
                ->with('error', 'Terjadi kesalahan saat menambahkan gedung: ' . $e->getMessage());
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
    public function update(Request $request, string $kode_gedung)
    {
        // Check for name uniqueness, excluding the current gedung
        $existingName = Gedung::where('nama_gedung', $request->nama_gedung)
            ->where('kode_gedung', '!=', $kode_gedung)
            ->exists();
            
        if ($existingName) {
            return redirect()->route('gedung.index')
                ->withErrors(['nama_gedung' => 'Nama gedung sudah digunakan.'], 'editGedung_' . $kode_gedung)
                ->withInput()
                ->with('showEditGedungModal', $kode_gedung);
        }

        $validator = Validator::make($request->all(), [
            'nama_gedung' => 'required|string|max:255|unique:gedung,nama_gedung,' . $kode_gedung . ',kode_gedung',
        ], [
            'nama_gedung.required' => 'Nama gedung harus diisi.',
            'nama_gedung.max' => 'Nama gedung maksimal 255 karakter.',
            'nama_gedung.unique' => 'Nama gedung sudah digunakan.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('gedung.index')
                ->withErrors($validator, 'editGedung_' . $kode_gedung)  // Use a named error bag
                ->withInput()
                ->with('showEditGedungModal', $kode_gedung);  // Flag to show modal
        }

        try {
            $gedung = Gedung::findOrFail($kode_gedung);
            $gedung->update([
                'nama_gedung' => $request->nama_gedung,
            ]);

            return redirect()->route('gedung.index')
                ->with('success', 'Gedung berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('gedung.index')
                ->with('error', 'Terjadi kesalahan saat memperbarui gedung.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $kode_gedung)
    {
        try {
            $gedung = Gedung::findOrFail($kode_gedung);
            
            // Check if gedung has any ruangan
            if ($gedung->ruangan()->count() > 0) {
                return redirect()->route('gedung.index')
                    ->with('error', 'Gedung tidak dapat dihapus karena masih memiliki ruangan.');
            }

            $gedung->delete();
            return redirect()->route('gedung.index')
                ->with('success', 'Gedung berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('gedung.index')
                ->with('error', 'Terjadi kesalahan saat menghapus gedung.');
        }
    }
}
