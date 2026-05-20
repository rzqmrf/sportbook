{{-- resources/views/admin/lapangan/show.blade.php --}}
@extends('layouts.admin')

@section('title', $lapangan->nama)
@section('page-title', $lapangan->nama)
@section('page-subtitle', 'Detail lapangan & riwayat reservasi')

@section('content')
<div class="max-w-6xl mx-auto pb-12">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <nav class="flex items-center text-xs text-gray-400 mb-1.5 gap-1.5">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-green-600 transition">Dashboard</a>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <a href="{{ route('admin.lapangan.index') }}" class="hover:text-green-600 transition">Lapangan</a>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <span class="text-gray-600 font-medium truncate max-w-xs">{{ $lapangan->nama }}</span>
            </nav>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-gray-900">{{ $lapangan->nama }}</h1>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold
                    {{ $lapangan->status === 'aktif' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $lapangan->status === 'aktif' ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                    {{ ucfirst($lapangan->status) }}
                </span>
            </div>
            <p class="text-sm text-gray-500 mt-0.5">{{ $lapangan->jenis }}</p>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('admin.lapangan.edit', $lapangan) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-medium text-sm transition shadow-sm shadow-amber-200">
                <i class="fa-solid fa-pen text-xs"></i> Edit
            </a>
            <a href="{{ route('admin.lapangan.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-600 rounded-xl font-medium text-sm hover:bg-gray-50 transition shadow-sm">
                <i class="fa-solid fa-arrow-left text-xs"></i> Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

        {{-- KOLOM KIRI: Info Lapangan --}}
        <div class="xl:col-span-1 space-y-4">

            {{-- Foto Card --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="h-52 bg-gray-100 relative overflow-hidden">
                    @if($lapangan->foto)
                        <img src="{{ asset('uploads/lapangan/'.$lapangan->foto) }}"
                             alt="{{ $lapangan->nama }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-green-50 to-green-100">
                            <i class="fa-solid fa-futbol text-green-200 text-6xl mb-2"></i>
                            <p class="text-xs text-green-400">Belum ada foto</p>
                        </div>
                    @endif
                </div>

                <div class="p-5 space-y-4">
                    {{-- Harga --}}
                    <div class="flex items-center justify-between py-3 px-4 bg-green-50 rounded-xl">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-wallet text-green-600 text-sm"></i>
                            <span class="text-sm text-green-700 font-medium">Harga / Jam</span>
                        </div>
                        <span class="text-base font-bold text-green-700">{{ $lapangan->harga_format }}</span>
                    </div>

                    {{-- Jam Operasional --}}
                    <div class="flex items-center justify-between py-3 px-4 bg-blue-50 rounded-xl">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-clock text-blue-600 text-sm"></i>
                            <span class="text-sm text-blue-700 font-medium">Jam Operasional</span>
                        </div>
                        <span class="text-sm font-bold text-blue-700">
                            {{ substr($lapangan->jam_buka, 0, 5) }} – {{ substr($lapangan->jam_tutup, 0, 5) }}
                        </span>
                    </div>

                    {{-- Total Reservasi --}}
                    <div class="flex items-center justify-between py-3 px-4 bg-purple-50 rounded-xl">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-calendar-check text-purple-600 text-sm"></i>
                            <span class="text-sm text-purple-700 font-medium">Total Reservasi</span>
                        </div>
                        <span class="text-sm font-bold text-purple-700">{{ $lapangan->reservasi->count() }} booking</span>
                    </div>

                    {{-- Deskripsi --}}
                    @if($lapangan->deskripsi)
                    <div class="pt-1 border-t border-gray-100">
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-2">Deskripsi</p>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $lapangan->deskripsi }}</p>
                    </div>
                    @endif
                </div>

                {{-- Danger Zone --}}
                <div class="px-5 pb-5 pt-1 border-t border-gray-50">
                    <form action="{{ route('admin.lapangan.destroy', $lapangan) }}" method="POST"
                          onsubmit="return confirm('Yakin ingin menghapus lapangan \'{{ $lapangan->nama }}\'? Tindakan ini tidak bisa dibatalkan.')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl border border-red-200 text-red-500 text-sm font-medium hover:bg-red-50 transition mt-3">
                            <i class="fa-solid fa-trash text-xs"></i> Hapus Lapangan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: Riwayat Reservasi --}}
        <div class="xl:col-span-2">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600">
                            <i class="fa-solid fa-list-check text-sm"></i>
                        </span>
                        <h3 class="font-semibold text-gray-800">Riwayat Reservasi</h3>
                    </div>
                    <span class="text-xs text-gray-400">{{ $lapangan->reservasi->count() }} total</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-gray-400 text-xs">
                                <th class="px-5 py-3 text-left font-medium">Pemesan</th>
                                <th class="px-5 py-3 text-left font-medium">Tanggal</th>
                                <th class="px-5 py-3 text-left font-medium">Jam</th>
                                <th class="px-5 py-3 text-left font-medium">Total</th>
                                <th class="px-5 py-3 text-left font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($reservasiTerbaru as $res)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-5 py-3.5">
                                    <p class="font-medium text-gray-800">{{ $res->nama_pemesan }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $res->no_hp }}</p>
                                </td>
                                <td class="px-5 py-3.5 text-gray-600">
                                    {{ \Carbon\Carbon::parse($res->tanggal)->format('d M Y') }}
                                </td>
                                <td class="px-5 py-3.5 text-gray-600 tabular-nums">
                                    {{ substr($res->jam_mulai, 0, 5) }} – {{ substr($res->jam_selesai, 0, 5) }}
                                </td>
                                <td class="px-5 py-3.5 font-semibold text-gray-800">
                                    {{ $res->total_format }}
                                </td>
                                <td class="px-5 py-3.5">
                                    @if($res->status === 'pending')
                                        <span class="inline-flex items-center gap-1 bg-amber-100 text-amber-700 text-xs font-semibold px-2.5 py-1 rounded-lg">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Pending
                                        </span>
                                    @elseif($res->status === 'approved')
                                        <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-lg">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Approved
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 bg-red-100 text-red-600 text-xs font-semibold px-2.5 py-1 rounded-lg">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Rejected
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-5 py-14 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <i class="fa-solid fa-calendar-xmark text-gray-200 text-4xl"></i>
                                        <p class="text-sm text-gray-400">Belum ada reservasi untuk lapangan ini.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
