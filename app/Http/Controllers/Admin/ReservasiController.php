<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use App\Models\Lapangan;
use Illuminate\Http\Request;

class ReservasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservasi::with('lapangan');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama_pemesan', 'like', '%' . $request->search . '%')
                  ->orWhere('no_hp', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('lapangan_id')) {
            $query->where('lapangan_id', $request->lapangan_id);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        $reservasi = $query->latest()->paginate(10)->withQueryString();
        $lapangan  = Lapangan::where('status', 'aktif')->get();

        $stats = [
            'pending'  => Reservasi::where('status', 'pending')->count(),
            'approved' => Reservasi::where('status', 'approved')->count(),
            'rejected' => Reservasi::where('status', 'rejected')->count(),
        ];

        return view('admin.reservasi.index', compact('reservasi', 'lapangan', 'stats'));
    }

    public function show(Reservasi $reservasi)
    {
        $reservasi->load('lapangan');
        return view('admin.reservasi.show', compact('reservasi'));
    }

    public function approve(Request $request, Reservasi $reservasi)
    {
        if ($reservasi->status !== 'pending') {
            return back()->with('error', 'Reservasi sudah diproses sebelumnya.');
        }

        // Cek konflik jadwal
        $konflik = Reservasi::where('lapangan_id', $reservasi->lapangan_id)
            ->where('tanggal', $reservasi->tanggal)
            ->where('status', 'approved')
            ->where('id', '!=', $reservasi->id)
            ->where(function($q) use ($reservasi) {
                $q->whereBetween('jam_mulai', [$reservasi->jam_mulai, $reservasi->jam_selesai])
                  ->orWhereBetween('jam_selesai', [$reservasi->jam_mulai, $reservasi->jam_selesai]);
            })->exists();

        if ($konflik) {
            return back()->with('error', 'Jadwal bentrok dengan reservasi yang sudah disetujui!');
        }

        $reservasi->update(['status' => 'approved', 'alasan_tolak' => null]);

        return back()->with('success', 'Reservasi berhasil disetujui!');
    }

    public function reject(Request $request, Reservasi $reservasi)
    {
        $request->validate([
            'alasan_tolak' => 'required|string|min:5',
        ], [
            'alasan_tolak.required' => 'Alasan penolakan wajib diisi.',
            'alasan_tolak.min'      => 'Alasan minimal 5 karakter.',
        ]);

        $reservasi->update([
            'status'       => 'rejected',
            'alasan_tolak' => $request->alasan_tolak,
        ]);

        return back()->with('success', 'Reservasi berhasil ditolak.');
    }

    public function destroy(Reservasi $reservasi)
    {
        $reservasi->delete();
        return redirect()->route('admin.reservasi.index')
            ->with('success', 'Data reservasi berhasil dihapus!');
    }
}
