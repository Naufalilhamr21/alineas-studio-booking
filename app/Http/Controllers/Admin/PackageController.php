<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;
use App\Models\Package;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil data terbaru (Pagination 10 per halaman)
        $packages = Package::latest()->paginate(10);
        return view('admin.packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.packages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // A. Validasi Input
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:packages,name',
            'tagline' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:1',
            'max_pax' => 'required|integer|min:1',
            'extra_price_per_pax' => 'required|numeric|min:0',
            'benefit' => 'nullable|string',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Wajib ada gambar, max 2MB
            'is_active' => 'boolean', // Opsional, default false jika tidak dicentang
        ], [
            // CUSTOM ERROR MESSAGES
            'price.numeric' => 'Format harga salah! Harap masukkan hanya angka (contoh: 150000).',
            'price.required' => 'Harga paket wajib diisi.',
            'duration_minutes.integer' => 'Durasi harus berupa angka bulat (menit).',
            'duration_minutes.required' => 'Durasi wajib diisi.',
            'thumbnail.required' => 'Anda wajib mengupload foto thumbnail.',
            'thumbnail.image' => 'File harus berupa gambar (JPG/PNG).',
        ]);

        // B. Handle Upload Gambar
        if ($request->hasFile('thumbnail')) {
            // Simpan ke folder 'packages' di dalam storage public
            $path = $request->file('thumbnail')->store('packages', 'public');
            $validated['thumbnail'] = $path;
        }

        // C. Generate Slug Otomatis dari Name
        $validated['slug'] = Str::slug($request->name);

        // D. Pastikan is_active terisi (checkbox HTML tidak mengirim value jika unchecked)
        $validated['is_active'] = $request->has('is_active');

        // E. Simpan ke Database
        Package::create($validated);

        // F. Redirect kembali ke Index dengan pesan sukses
        return redirect()->route('admin.packages.index')->with('success', 'Package created successfully!');
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
    public function edit(Package $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Package $package)
    {
        // A. Validasi
        $validated = $request->validate([
            // PENTING: Unique validasi harus mengecualikan ID paket ini sendiri
            // Agar tidak error "Nama sudah ada" saat kita save tanpa ganti nama
            'name' => 'required|string|max:255|unique:packages,name,' . $package->id,
            'tagline' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:1',
            'max_pax' => 'required|integer|min:1',
            'extra_price_per_pax' => 'required|numeric|min:0',
            'benefit' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Boleh kosong (nullable) jika tidak ganti foto
            'is_active' => 'boolean',
        ], [
            'name.unique' => 'Nama paket ini sudah digunakan oleh paket lain.',
            'price.numeric' => 'Format harga salah! Harap masukkan angka.',
            'thumbnail.image' => 'File harus berupa gambar (JPG/PNG).',
        ]);

        // B. Handle Upload Gambar Baru (Jika Ada)
        if ($request->hasFile('thumbnail')) {
            // 1. Hapus gambar lama dulu agar server tidak penuh
            if ($package->thumbnail && Storage::disk('public')->exists($package->thumbnail)) {
                Storage::disk('public')->delete($package->thumbnail);
            }
            
            // 2. Simpan gambar baru
            $path = $request->file('thumbnail')->store('packages', 'public');
            $validated['thumbnail'] = $path;
        }

        // C. Update Slug jika Nama Berubah
        if ($request->name !== $package->name) {
            $validated['slug'] = Str::slug($request->name);
        }

        // D. Checkbox Handling
        // Jika di form checkbox dicentang -> true, jika tidak -> false
        // Kita gunakan $request->has karena checkbox HTML tidak kirim value jika unchecked
        // TAPI untuk update, kita set manual karena $validated hanya berisi field yang divalidasi
        $package->is_active = $request->has('is_active');
        
        // E. Update Data (Otomatis hanya field yang ada di $validated)
        $package->update($validated);
        
        // Karena checkbox 'is_active' mungkin tidak masuk $validated (karena boolean), kita save manual statusnya
        // atau cara lebih rapi: masukkan is_active ke $validated sebelum update
        $package->update([
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.packages.index')->with('success', 'Paket berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(package $package)
    {
        // 1. Hapus Gambar dari Storage (Jika ada)
        if ($package->thumbnail && Storage::disk('public')->exists($package->thumbnail)) {
            Storage::disk('public')->delete($package->thumbnail);
        }

        // 2. Hapus Data dari Database
        $package->delete();

        // 3. Kembali dengan pesan sukses
        return redirect()->route('admin.packages.index')->with('success', 'Paket berhasil dihapus!');
    }
}
