@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat datang kembali, ' . Auth::user()->name)

@section('content')

{{-- STAT CARDS --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">
    <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-table-tennis-paddle-ball text-green-600 text-2xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Lapangan</p>
            <p class="text-3xl font-bold text-gray-800">{{ $totalLapangan }}</p>
        </div>
    </div>

    <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-calendar-check text-blue-600 text-2xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Reservasi</p>
            <p class="text-3xl font-bold text-gray-800">{{ $totalReservasi }}</p>
        </div>
    </div>

    <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-14 h-14 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-clock text-amber-600 text-2xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Pending</p>
            <p class="text-3xl font-bold text-gray-800">{{ $pendingReservasi }}</p>
            @if($pendingReservasi > 0)
                <a href="{{ route('admin.reservasi.index', ['status'=>'pending']) }}" class="text-xs text-amber-600 hover:underline">Lihat semua →</a>
            @endif
        </div>
    </div>

    <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-14 h-14 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-wallet text-emerald-600 text-2xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Pendapatan Bulan Ini</p>
            <p class="text-xl font-bold text-gray-800">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</p>
        </div>
    </div>
</div>

{{-- CHARTS ROW 1 --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5 mb-5">
    {{-- Reservasi 7 hari --}}
    <div class="xl:col-span-2 bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-semibold text-gray-800">Reservasi 7 Hari Terakhir</h3>
                <p class="text-xs text-gray-400">Jumlah booking masuk per hari</p>
            </div>
            <div class="w-9 h-9 bg-blue-50 rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-chart-line text-blue-500"></i>
            </div>
        </div>
        <canvas id="chartReservasi" height="100"></canvas>
    </div>

    {{-- Donut jenis lapangan --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-semibold text-gray-800">Reservasi per Jenis</h3>
                <p class="text-xs text-gray-400">Distribusi jenis lapangan</p>
            </div>
            <div class="w-9 h-9 bg-purple-50 rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-chart-pie text-purple-500"></i>
            </div>
        </div>
        <canvas id="chartDonut" height="180"></canvas>
        <div class="mt-4 space-y-1.5" id="donutLegend"></div>
    </div>
</div>

{{-- CHARTS ROW 2 --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5 mb-5">
    {{-- Pendapatan 6 bulan --}}
    <div class="xl:col-span-2 bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-semibold text-gray-800">Pendapatan 6 Bulan Terakhir</h3>
                <p class="text-xs text-gray-400">Total pendapatan dari reservasi approved</p>
            </div>
            <div class="w-9 h-9 bg-green-50 rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-chart-bar text-green-500"></i>
            </div>
        </div>
        <canvas id="chartPendapatan" height="100"></canvas>
    </div>

    {{-- Lapangan terpopuler --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <h3 class="font-semibold text-gray-800 mb-4">Lapangan Terpopuler</h3>
        <div class="space-y-3">
            @foreach($lapanganPopuler as $i => $lap)
            <div class="flex items-center gap-3">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-bold flex-shrink-0
                    {{ $i === 0 ? 'bg-amber-400 text-white' : ($i === 1 ? 'bg-gray-300 text-gray-700' : 'bg-orange-300 text-white') }}">
                    {{ $i + 1 }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $lap->nama }}</p>
                    <div class="w-full bg-gray-100 rounded-full h-1.5 mt-1">
                        @php $maxCount = $lapanganPopuler->first()->reservasi_count ?: 1; @endphp
                        <div class="bg-green-500 h-1.5 rounded-full"
                             style="width: {{ ($lap->reservasi_count / $maxCount) * 100 }}%"></div>
                    </div>
                </div>
                <span class="text-sm font-bold text-gray-600 flex-shrink-0">{{ $lap->reservasi_count }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- RESERVASI TERBARU --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-800">Reservasi Terbaru</h3>
        <a href="{{ route('admin.reservasi.index') }}" class="text-sm text-green-600 hover:underline">Lihat semua →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="px-5 py-3 text-left">Pemesan</th>
                    <th class="px-5 py-3 text-left">Lapangan</th>
                    <th class="px-5 py-3 text-left">Tanggal</th>
                    <th class="px-5 py-3 text-left">Jam</th>
                    <th class="px-5 py-3 text-left">Total</th>
                    <th class="px-5 py-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($reservasiTerbaru as $res)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-3 font-medium text-gray-800">
                        <div>{{ $res->nama_pemesan }}</div>
                        <div class="text-xs text-gray-400">{{ $res->no_hp }}</div>
                    </td>
                    <td class="px-5 py-3 text-gray-600">{{ $res->lapangan->nama ?? '-' }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ \Carbon\Carbon::parse($res->tanggal)->format('d M Y') }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ substr($res->jam_mulai,0,5) }} – {{ substr($res->jam_selesai,0,5) }}</td>
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $res->total_format }}</td>
                    <td class="px-5 py-3">
                        @if($res->status === 'pending')
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-1 rounded-full">Pending</span>
                        @elseif($res->status === 'approved')
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-1 rounded-full">Approved</span>
                        @else
                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-1 rounded-full">Rejected</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-8 text-center text-gray-400">Belum ada reservasi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
const colors = ['#16a34a','#2563eb','#f59e0b','#dc2626','#7c3aed','#0891b2'];

// Chart 1: Reservasi 7 hari
new Chart(document.getElementById('chartReservasi'), {
    type: 'line',
    data: {
        labels: {!! json_encode($chartLabels) !!},
        datasets: [{
            label: 'Reservasi',
            data: {!! json_encode($chartReservasi) !!},
            borderColor: '#16a34a',
            backgroundColor: 'rgba(22,163,74,0.08)',
            borderWidth: 2.5,
            pointBackgroundColor: '#16a34a',
            pointRadius: 4,
            tension: 0.4,
            fill: true,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f3f4f6' } },
            x: { grid: { display: false } }
        }
    }
});

// Chart 2: Donut per jenis
const donutLabels = {!! json_encode($reservasiPerJenis->pluck('jenis')) !!};
const donutData   = {!! json_encode($reservasiPerJenis->pluck('total')) !!};
new Chart(document.getElementById('chartDonut'), {
    type: 'doughnut',
    data: {
        labels: donutLabels,
        datasets: [{
            data: donutData,
            backgroundColor: colors,
            borderWidth: 2,
            borderColor: '#fff',
        }]
    },
    options: {
        responsive: true,
        cutout: '65%',
        plugins: { legend: { display: false } }
    }
});

// Donut legend
const legend = document.getElementById('donutLegend');
donutLabels.forEach((l, i) => {
    legend.innerHTML += `
        <div class="flex items-center justify-between text-xs">
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 rounded-full flex-shrink-0" style="background:${colors[i]}"></div>
                <span class="text-gray-600">${l}</span>
            </div>
            <span class="font-medium text-gray-800">${donutData[i]}</span>
        </div>`;
});

// Chart 3: Pendapatan bar
new Chart(document.getElementById('chartPendapatan'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($chartBulan) !!},
        datasets: [{
            label: 'Pendapatan (Rp)',
            data: {!! json_encode($chartPendapatan) !!},
            backgroundColor: colors.map(c => c + '99'),
            borderColor: colors,
            borderWidth: 1.5,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: '#f3f4f6' },
                ticks: {
                    callback: v => 'Rp ' + (v/1000).toLocaleString('id-ID') + 'K'
                }
            },
            x: { grid: { display: false } }
        }
    }
});
</script>
@endpush
