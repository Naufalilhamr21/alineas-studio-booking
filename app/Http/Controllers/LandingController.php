<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Package;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        // Ambil 4 paket pertama untuk ditampilkan di halaman depan
        $packages = Package::with(['galleries' => function($query) {
            $query->latest();
        }])->take(4)->get();

        return view('welcome', compact('packages'));
    }

    public function pricelist()
    {
        // Ambil SEMUA paket yang aktif, urutkan dari yang terbaru
        $packages = Package::where('is_active', true)->latest()->get();
        
        return view('pricelist', compact('packages'));
    }

    public function show(\App\Models\Package $package)
    {
        $latestPhotos = $package->galleries()->latest()->take(4)->get();

        return view('package-detail', compact('package', 'latestPhotos'));
    }

    public function gallery()
    {
        // 1. Ambil semua paket yang MEMILIKI galeri saja (agar tab filter tidak kosong)
        $packages = \App\Models\Package::has('galleries')->get();

        // 2. Ambil semua foto galeri urut terbaru (Eager load paketnya biar ringan)
        $galleries = \App\Models\Gallery::with('package')->latest()->get();


        return view('gallery', compact('packages', 'galleries'));
    }
}