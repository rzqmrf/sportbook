@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')
@section('page-subtitle')
    Selamat datang kembali, {{ Auth::user()->name ?? 'Administrator' }}
@endsection

@section('content')

<!-- Stat Cards Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Stat 1: Total Lapangan -->
    <div class="bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm hover:shadow-md transition duration-300 flex items-center gap-5">
        <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 border border-emerald-100 flex-shrink-0">
            <i class="fa-solid fa-table-tennis-paddle-ball text-2xl"></i>
        </div>
        <div>
            <span class="text-[11px] font-black text-slate-400 uppercase tracking-wider block">Total Lapangan</span>
            <span class="text-3xl font-black text-slate-800 mt-1 block">{{ $totalLapangan }}</span>
        </div>
    </div>

    <!-- Stat 2: Total Reservasi -->
    <div class="bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm hover:shadow-md transition duration-300 flex items-center gap-5">
        <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 border border-blue-100 flex-shrink-0">
            <i class="fa-solid fa-calendar-check text-2xl"></i>
        </div>
        <div>
            <span class="text-[11px] font-black text-slate-400 uppercase tracking-wider block">Total Reservasi</span>
            <span class="text-3xl font-black text-slate-800 mt-1 block">{{ $totalReservasi }}</span>
        </div>
    </div>

    <!-- Stat 3: Menunggu Konfirmasi -->
    <div class="bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm hover:shadow-md transition duration-300 flex items-center gap-5">
        <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600 border border-amber-100 flex-shrink-0">
            <i class="fa-solid fa-hourglass-half text-2xl"></i>
        </div>
        <div class="flex-1 min-w-0">
            <span class="text-[11px] font-black text-slate-400 uppercase tracking-wider block">Persetujuan Pending</span>
            <span class="text-3xl font-black text-slate-800 mt-1 block">{{ $pendingReservasi }}</span>
            @if($pendingReservasi > 0)
                <a href="{{ route('admin.reservasi.index', ['status' => 'pending']) }}" class="text-[10px] font-extrabold text-amber-600 hover:text-amber-700 uppercase tracking-wider mt-1 inline-flex items-center gap-1">
                    Konfirmasi Sekarang <i class="fa-solid fa-arrow-right text-[8px]"></i>
                </a>
            @endif
        </div>
    </div>

    <!-- Stat 4: Estimasi Pendapatan -->
    <div class="bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm hover:shadow-md transition duration-300 flex items-center gap-5">
        <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 border border-indigo-100 flex-shrink-0">
            <i class="fa-solid fa-wallet text-2xl"></i>
        </div>
        <div>
            <span class="text-[11px] font-black text-slate-400 uppercase tracking-wider block">Pendapatan Bulan Ini</span>
            <span class="text-xl font-black text-slate-800 mt-2 block">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</span>
        </div>
    </div>
</div>

<!-- Charts section -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <!-- Chart: Line Chart (Reservasi 7 Hari) -->
    <div class="xl:col-span-2 bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <div>
                <span class="text-[10px] font-black text-primary uppercase tracking-widest block">Analitik Volume</span>
                <h3 class="text-lg font-black text-slate-800 mt-1">Tren Booking Masuk (7 Hari)</h3>
            </div>
            <div class="w-10 h-10 bg-slate-50 border border-slate-200/60 rounded-xl flex items-center justify-center text-slate-400">
                <i class="fa-solid fa-chart-line text-sm"></i>
            </div>
        </div>
        <div class="relative h-64">
            <canvas id="chartReservasi"></canvas>
        </div>
    </div>

    <!-- Chart: Donut Chart (Jenis Lapangan) -->
    <div class="bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm flex flex-col">
        <div class="flex items-center justify-between mb-6">
            <div>
                <span class="text-[10px] font-black text-royal uppercase tracking-widest block">Distribusi Cabang</span>
                <h3 class="text-lg font-black text-slate-800 mt-1">Persentase Booking</h3>
            </div>
            <div class="w-10 h-10 bg-slate-50 border border-slate-200/60 rounded-xl flex items-center justify-center text-slate-400">
                <i class="fa-solid fa-chart-pie text-sm"></i>
            </div>
        </div>
        <div class="relative h-44 flex items-center justify-center">
            <canvas id="chartDonut"></canvas>
        </div>
        <!-- Donut Custom Legend Grid -->
        <div class="grid grid-cols-2 gap-3 mt-6 pt-5 border-t border-slate-100" id="donutLegend"></div>
    </div>
</div>

<!-- Chart Row 2: Monthly Income -->
<div class="bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm">
    <div class="flex items-center justify-between mb-6">
        <div>
            <span class="text-[10px] font-black text-indigo-600 uppercase tracking-widest block">Laporan Keuangan</span>
            <h3 class="text-lg font-black text-slate-800 mt-1">Pendapatan 6 Bulan Terakhir</h3>
        </div>
        <div class="w-10 h-10 bg-slate-50 border border-slate-200/60 rounded-xl flex items-center justify-center text-slate-400">
            <i class="fa-solid fa-money-bill-trend-up text-sm"></i>
        </div>
    </div>
    <div class="relative h-60">
        <canvas id="chartPendapatan"></canvas>
    </div>
</div>

<!-- Bottom Section: Reservasi Terbaru & Lapangan Terpopuler -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    
    <!-- Left: Reservasi Terbaru Table (2/3) -->
    <div class="xl:col-span-2 bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm overflow-hidden flex flex-col">
        <div class="flex items-center justify-between mb-5">
            <div>
                <span class="text-[10px] font-black text-indigo uppercase tracking-widest block">Update Terkini</span>
                <h3 class="text-lg font-black text-slate-800 mt-1">Reservasi Terbaru</h3>
            </div>
            <a href="{{ route('admin.reservasi.index') }}" class="text-xs font-black text-primary hover:underline flex items-center gap-1">
                Semua Reservasi <i class="fa-solid fa-angle-right"></i>
            </a>
        </div>
        
        <div class="overflow-x-auto -mx-6 -mb-6">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 border-b border-slate-100">
                        <th class="px-6 py-4.5 font-bold uppercase tracking-wider">Pemesan</th>
                        <th class="px-6 py-4.5 font-bold uppercase tracking-wider">Arena</th>
                        <th class="px-6 py-4.5 font-bold uppercase tracking-wider">Jadwal Sewa</th>
                        <th class="px-6 py-4.5 font-bold uppercase tracking-wider">Total Pembayaran</th>
                        <th class="px-6 py-4.5 font-bold uppercase tracking-wider text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-semibold text-slate-600">
                    @forelse($reservasiTerbaru as $res)
                    <tr class="hover:bg-slate-50/50 transition duration-150">
                        <td class="px-6 py-4">
                            <span class="text-slate-800 font-bold block">{{ $res->nama_pemesan }}</span>
                            <span class="text-[10px] text-slate-400 block mt-0.5"><i class="fa-solid fa-phone text-[9px] mr-1"></i>{{ $res->no_hp }}</span>
                        </td>
                        <td class="px-6 py-4 text-slate-800">
                            {{ $res->lapangan->nama ?? 'Lapangan' }}
                        </td>
                        <td class="px-6 py-4">
                            <span>{{ \Carbon\Carbon::parse($res->tanggal)->format('d M Y') }}</span>
                            <span class="text-[10px] text-slate-400 block mt-0.5"><i class="fa-regular fa-clock text-[9px] mr-1"></i>{{ substr($res->jam_mulai,0,5) }} – {{ substr($res->jam_selesai,0,5) }} WIB</span>
                        </td>
                        <td class="px-6 py-4 text-slate-800 font-bold">
                            {{ $res->total_format }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($res->status === 'pending')
                                <span class="bg-amber-100 text-amber-800 text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wider">Pending</span>
                            @elseif($res->status === 'approved')
                                <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wider">Approved</span>
                            @else
                                <span class="bg-rose-100 text-rose-800 text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wider">Rejected</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                            <i class="fa-solid fa-calendar-xmark text-3xl mb-3 text-slate-300 block"></i>
                            Belum ada reservasi sewa terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Right: Lapangan Terpopuler (1/3) -->
    <div class="bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm flex flex-col">
        <div>
            <span class="text-[10px] font-black text-rose-600 uppercase tracking-widest block">Ranking Arena</span>
            <h3 class="text-lg font-black text-slate-800 mt-1 mb-5">Lapangan Terpopuler</h3>
            
            <div class="space-y-4">
                @forelse($lapanganPopuler as $i => $lap)
                <div class="flex items-center gap-3 bg-slate-50/50 hover:bg-slate-50 border border-slate-100 p-3.5 rounded-2xl transition duration-200">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-black flex-shrink-0
                        {{ $i === 0 ? 'bg-amber-100 text-amber-700' : ($i === 1 ? 'bg-slate-100 text-slate-700' : 'bg-orange-100 text-orange-700') }}">
                        #{{ $i + 1 }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-black text-slate-800 truncate">{{ $lap->nama }}</p>
                        <div class="flex items-center justify-between mt-1">
                            <span class="text-[9px] font-bold text-slate-400 uppercase">{{ $lap->jenis }}</span>
                            <span class="text-[10px] font-extrabold text-slate-600">{{ $lap->reservasi_count }} Booking</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-1.5 mt-2 overflow-hidden">
                            @php 
                                $maxCount = $lapanganPopuler->first()->reservasi_count ?: 1;
                                $percentage = ($lap->reservasi_count / $maxCount) * 100;
                            @endphp
                            <div class="bg-gradient-to-r from-primary to-royal h-full rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-slate-400 text-xs">
                    Belum ada data transaksi sewa.
                </div>
                @endforelse
            </div>
        </div>
        
        <div class="text-[10px] text-slate-400 font-medium italic border-t border-slate-100 pt-4 mt-6">
            * Peringkat dihitung secara otomatis berdasarkan frekuensi transaksi sewa disetujui.
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Beautiful color palette
    const colors = ['#059669', '#2563eb', '#f59e0b', '#7c3aed', '#ec4899', '#06b6d4'];

    // 1. Chart Line (Reservasi 7 Hari) - Real Live Data
    const ctxReservasi = document.getElementById('chartReservasi').getContext('2d');
    const gradient = ctxReservasi.createLinearGradient(0, 0, 0, 200);
    gradient.addColorStop(0, 'rgba(5, 150, 105, 0.15)');
    gradient.addColorStop(1, 'rgba(5, 150, 105, 0)');

    new Chart(ctxReservasi, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Reservasi',
                data: {!! json_encode($chartReservasi) !!},
                borderColor: '#059669',
                backgroundColor: gradient,
                borderWidth: 3,
                pointBackgroundColor: '#059669',
                pointHoverRadius: 6,
                tension: 0.35,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { 
                    beginAtZero: true, 
                    grid: { color: '#f1f5f9' },
                    ticks: { color: '#94a3b8', font: { weight: 'bold', size: 10 }, stepSize: 1 }
                },
                x: { 
                    grid: { display: false },
                    ticks: { color: '#94a3b8', font: { weight: 'bold', size: 10 } }
                }
            }
        }
    });

    // 2. Chart Donut (Jenis Lapangan) - Real Live Data
    const donutLabels = {!! json_encode($reservasiPerJenis->pluck('jenis')) !!};
    const donutData = {!! json_encode($reservasiPerJenis->pluck('total')) !!};
    
    if (donutLabels.length > 0) {
        const ctxDonut = document.getElementById('chartDonut').getContext('2d');
        new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: donutLabels,
                datasets: [{
                    data: donutData,
                    backgroundColor: colors,
                    borderWidth: 3,
                    borderColor: '#ffffff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: { legend: { display: false } }
            }
        });

        // Donut Legend Renderer
        const legend = document.getElementById('donutLegend');
        donutLabels.forEach((label, i) => {
            legend.innerHTML += `
                <div class="flex items-center justify-between text-[11px] font-bold">
                    <div class="flex items-center gap-2 min-w-0">
                        <div class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:${colors[i % colors.length]}"></div>
                        <span class="text-slate-500 truncate">${label}</span>
                    </div>
                    <span class="text-slate-800">${donutData[i]}</span>
                </div>`;
        });
    } else {
        document.getElementById('chartDonut').parentElement.innerHTML = `
            <div class="flex items-center justify-center h-full text-slate-400 text-xs">
                Tidak ada data sewa aktif.
            </div>`;
    }

    // 3. Chart Pendapatan (Bar Chart - 6 Bulan) - Real Live Data
    const ctxPendapatan = document.getElementById('chartPendapatan').getContext('2d');
    new Chart(ctxPendapatan, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartBulan) !!},
            datasets: [{
                label: 'Pendapatan',
                data: {!! json_encode($chartPendapatan) !!},
                backgroundColor: '#2563eb',
                hoverBackgroundColor: '#1d4ed8',
                borderRadius: 8,
                maxBarThickness: 40
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { 
                    beginAtZero: true, 
                    grid: { color: '#f1f5f9' },
                    ticks: { 
                        color: '#94a3b8', 
                        font: { weight: 'bold', size: 10 },
                        callback: function(value) {
                            return 'Rp ' + (value/1000).toLocaleString('id-ID') + 'k';
                        }
                    }
                },
                x: { 
                    grid: { display: false },
                    ticks: { color: '#94a3b8', font: { weight: 'bold', size: 10 } }
                }
            }
        }
    });
});
</script>
@endpush
