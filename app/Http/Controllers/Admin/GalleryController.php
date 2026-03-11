<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    // Halaman Utama (Tabel)
    public function index()
    {
        // Ambil data galeri + relasi paketnya
        $galleries = Gallery::with('package')->latest()->get();
        return view('admin.galleries.index', compact('galleries'));
    }

    // Halaman Tambah Foto (Form)
    public function create()
    {
        $packages = Package::all();
        return view('admin.galleries.create', compact('packages'));
    }

    // Proses Simpan
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'package_id' => 'nullable|exists:packages,id',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->storePublicly('galleries', 's3');

            Gallery::create([
                'image_path' => $path,
                'package_id' => $request->package_id,
                'is_featured' => $request->has('is_featured') ? true : false,
            ]);
        }

        // Redirect ke halaman Index (Tabel)
        return redirect()->route('admin.galleries.index')->with('success', 'Foto berhasil ditambahkan!');
    }

    //Halaman Edit Foto
    public function edit(Gallery $gallery)
    {
        $packages = Package::all();
        return view('admin.galleries.edit', compact('gallery', 'packages'));
    }

    // Proses Update Foto
    public function update(Request $request, Gallery $gallery)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Nullable karena tidak wajib ganti foto
            'package_id' => 'nullable|exists:packages,id',
        ]);

        $data = [
            'package_id' => $request->package_id,
            'is_featured' => $request->has('is_featured') ? true : false,
        ];

        // Jika admin mengupload foto baru
        if ($request->hasFile('image')) {
            // Hapus foto lama dari storage
            if (Storage::disk('s3')->exists($gallery->image_path)) {
                Storage::disk('s3')->delete($gallery->image_path);
            }
            
            // Simpan foto baru
            $data['image_path'] = $request->file('image')->storePublicly('galleries', 's3');
        }

        $gallery->update($data);

        return redirect()->route('admin.galleries.index')->with('success', 'Data galeri berhasil diperbarui!');
    }

    // Hapus
    public function destroy(Gallery $gallery)
    {
        if (Storage::disk('s3')->exists($gallery->image_path)) {
            Storage::disk('s3')->delete($gallery->image_path);
        }

        $gallery->delete();

        return back()->with('success', 'Foto berhasil dihapus!');
    }
}