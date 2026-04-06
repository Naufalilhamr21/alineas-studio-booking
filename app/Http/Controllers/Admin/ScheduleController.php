<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudioSchedule; // Update Import
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = StudioSchedule::orderBy('date', 'desc')->get();
        return view('admin.schedules.index', compact('schedules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date|unique:studio_schedules,date',
            'status_tipe' => 'required|in:tutup,jam_khusus',
            'open_time' => 'required_if:status_tipe,jam_khusus',
            'close_time' => 'required_if:status_tipe,jam_khusus',
            'reason' => 'nullable|string|max:255'
        ], [
            // Pesan Eror Khusus Berbahasa Indonesia
            'date.required' => 'Tanggal wajib dipilih.',
            'date.date' => 'Format tanggal tidak valid.',
            'date.unique' => 'Pengaturan jadwal untuk tanggal ini sudah dibuat sebelumnya.',
            'status_tipe.required' => 'Tipe pengaturan wajib dipilih.',
            'status_tipe.in' => 'Pilihan tipe pengaturan tidak valid.',
            'open_time.required_if' => 'Jam Buka wajib diisi jika memilih opsi Jam Khusus.',
            'close_time.required_if' => 'Jam Tutup wajib diisi jika memilih opsi Jam Khusus.',
            'reason.max' => 'Keterangan terlalu panjang, maksimal 255 karakter.'
        ]);

        $isClosed = $request->status_tipe === 'tutup';

        StudioSchedule::create([
            'date' => $request->date,
            'is_closed' => $isClosed,
            'open_time' => $isClosed ? null : $request->open_time,
            'close_time' => $isClosed ? null : $request->close_time,
            'reason' => $request->reason
        ]);

        return back()->with('success', 'Pengaturan jadwal berhasil disimpan!');
    }

    public function destroy($id)
    {
        StudioSchedule::findOrFail($id)->delete();
        return back()->with('success', 'Jadwal khusus dibatalkan, kembali ke jam default.');
    }
}