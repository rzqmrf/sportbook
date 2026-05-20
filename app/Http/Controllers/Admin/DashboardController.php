<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use App\Models\Reservasi;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik utama
        $totalLapangan   = Lapangan::count();
        $totalReservasi  = Reservasi::count();
        $pendingReservasi = Reservasi::where('status', 'pending')->count();
        $pendapatanBulanIni = Reservasi::where('status', 'approved')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('total_harga');

        // Grafik reservasi 7 hari terakhir
        $chartReservasi = [];
        $chartLabels    = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartLabels[]    = $date->translatedFormat('D, d M');
            $chartReservasi[] = Reservasi::whereDate('created_at', $date->toDateString())->count();
        }

        // Grafik pendapatan 6 bulan terakhir
        $chartPendapatan = [];
        $chartBulan      = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $chartBulan[]      = $date->translatedFormat('M Y');
            $chartPendapatan[] = (float) Reservasi::where('status', 'approved')
                ->whereMonth('tanggal', $date->month)
                ->whereYear('tanggal', $date->year)
                ->sum('total_harga');
        }

        // Grafik reservasi per jenis lapangan (donut)
        $reservasiPerJenis = Reservasi::join('lapangan', 'reservasi.lapangan_id', '=', 'lapangan.id')
            ->select('lapangan.jenis', DB::raw('count(*) as total'))
            ->groupBy('lapangan.jenis')
            ->get();

        // Reservasi terbaru
        $reservasiTerbaru = Reservasi::with('lapangan')
            ->latest()
            ->take(8)
            ->get();

        // Lapangan terpopuler
        $lapanganPopuler = Lapangan::withCount('reservasi')
            ->orderBy('reservasi_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalLapangan', 'totalReservasi', 'pendingReservasi', 'pendapatanBulanIni',
            'chartLabels', 'chartReservasi',
            'chartBulan', 'chartPendapatan',
            'reservasiPerJenis',
            'reservasiTerbaru', 'lapanganPopuler'
        ));
    }
}
