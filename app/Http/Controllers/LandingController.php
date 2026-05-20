<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use App\Models\Reservasi;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $lapangan = Lapangan::where('status', 'aktif')->get();
        return view('landing.index', compact('lapangan'));
    }

    public function reservasi()
    {
        $lapangan = Lapangan::where('status', 'aktif')->get();
        return view('landing.reservasi', compact('lapangan'));
    }

    public function storeReservasi(Request $request)
    {
        $request->validate([
            'lapangan_id' => 'required|exists:lapangan,id',
            'nama_pemesan'=> 'required|string|max:100',
            'no_hp'       => 'required|string|max:20',
            'email'       => 'nullable|email',
            'tanggal'     => 'required|date|after_or_equal:today',
            'jam_mulai'   => 'required',
            'durasi_jam'  => 'required|integer|min:1|max:8',
            'catatan'     => 'nullable|string|max:500',
        ], [
            'lapangan_id.required' => 'Pilih lapangan terlebih dahulu.',
            'nama_pemesan.required'=> 'Nama wajib diisi.',
            'no_hp.required'       => 'Nomor HP wajib diisi.',
            'tanggal.after_or_equal'=> 'Tanggal tidak boleh di masa lalu.',
            'durasi_jam.min'       => 'Durasi minimal 1 jam.',
            'durasi_jam.max'       => 'Durasi maksimal 8 jam.',
        ]);

        $lapangan   = Lapangan::findOrFail($request->lapangan_id);
        $jamMulai   = $request->jam_mulai;
        $durasi     = (int) $request->durasi_jam;
        $jamSelesai = date('H:i', strtotime($jamMulai . ' +' . $durasi . ' hours'));
        $total      = $lapangan->harga_per_jam * $durasi;

        // Cek apakah slot masih tersedia
        $bentrok = Reservasi::where('lapangan_id', $request->lapangan_id)
            ->where('tanggal', $request->tanggal)
            ->where('status', 'approved')
            ->where(function($q) use ($jamMulai, $jamSelesai) {
                $q->whereBetween('jam_mulai', [$jamMulai, $jamSelesai])
                  ->orWhereBetween('jam_selesai', [$jamMulai, $jamSelesai]);
            })->exists();

        if ($bentrok) {
            return back()->withInput()->with('error', 'Maaf, jadwal tersebut sudah dipesan. Silakan pilih waktu lain.');
        }

        Reservasi::create([
            'lapangan_id'  => $request->lapangan_id,
            'nama_pemesan' => $request->nama_pemesan,
            'no_hp'        => $request->no_hp,
            'email'        => $request->email,
            'tanggal'      => $request->tanggal,
            'jam_mulai'    => $jamMulai,
            'jam_selesai'  => $jamSelesai,
            'durasi_jam'   => $durasi,
            'total_harga'  => $total,
            'catatan'      => $request->catatan,
            'status'       => 'pending',
        ]);

        return redirect()->route('reservasi')->with('success', 'Reservasi berhasil dikirim! Tunggu konfirmasi dari admin.');
    }
}
