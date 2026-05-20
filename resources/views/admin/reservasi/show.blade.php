@extends('layouts.admin')

@section('title', 'Detail Reservasi #'.$reservasi->id)
@section('page-title', 'Detail Reservasi')
@section('page-subtitle', 'Informasi lengkap pemesanan')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Header --}}
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h2 class="font-bold text-gray-800 text-lg">Reservasi #{{ $reservasi->id }}</h2>
                <p class="text-xs text-gray-400 mt-0.5">Dibuat: {{ $reservasi->created_at->format('d M Y, H:i') }}</p>
            </div>
            @if($reservasi->status === 'pending')
                <span class="bg-yellow-100 text-yellow-800 font-medium px-4 py-2 rounded-full text-sm">⏳ Pending</span>
            @elseif($reservasi->status === 'approved')
                <span class="bg-green-100 text-green-800 font-medium px-4 py-2 rounded-full text-sm">✅ Approved</span>
            @else
                <span class="bg-red-100 text-red-800 font-medium px-4 py-2 rounded-full text-sm">❌ Rejected</span>
            @endif
        </div>

        <div class="p-6 space-y-6">
            {{-- Info Pemesan --}}
            <div>
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Informasi Pemesan</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs text-gray-400 mb-1">Nama Lengkap</p>
                        <p class="font-semibold text-gray-800">{{ $reservasi->nama_pemesan }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs text-gray-400 mb-1">No. HP</p>
                        <p class="font-semibold text-gray-800">{{ $reservasi->no_hp }}</p>
                    </div>
                    @if($reservasi->email)
                    <div class="bg-gray-50 rounded-xl p-4 col-span-2">
                        <p class="text-xs text-gray-400 mb-1">Email</p>
                        <p class="font-semibold text-gray-800">{{ $reservasi->email }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Info Booking --}}
            <div>
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Detail Booking</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-xl p-4 col-span-2">
                        <p class="text-xs text-gray-400 mb-1">Lapangan</p>
                        <p class="font-semibold text-gray-800">{{ $reservasi->lapangan->nama ?? '-' }}</p>
                        <p class="text-xs text-gray-500">{{ $reservasi->lapangan->jenis ?? '' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs text-gray-400 mb-1">Tanggal</p>
                        <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($reservasi->tanggal)->translatedFormat('l, d F Y') }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs text-gray-400 mb-1">Jam</p>
                        <p class="font-semibold text-gray-800">{{ substr($reservasi->jam_mulai,0,5) }} – {{ substr($reservasi->jam_selesai,0,5) }}</p>
                        <p class="text-xs text-gray-500">{{ $reservasi->durasi_jam }} jam</p>
                    </div>
                    <div class="bg-green-50 border border-green-200 rounded-xl p-4 col-span-2">
                        <p class="text-xs text-green-600 mb-1">Total Harga</p>
                        <p class="text-2xl font-bold text-green-700">{{ $reservasi->total_format }}</p>
                    </div>
                </div>
            </div>

            @if($reservasi->catatan)
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                <p class="text-xs text-blue-600 font-medium mb-1">Catatan dari Pemesan</p>
                <p class="text-sm text-blue-800">{{ $reservasi->catatan }}</p>
            </div>
            @endif

            @if($reservasi->status === 'rejected' && $reservasi->alasan_tolak)
            <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                <p class="text-xs text-red-600 font-medium mb-1">Alasan Penolakan</p>
                <p class="text-sm text-red-800">{{ $reservasi->alasan_tolak }}</p>
            </div>
            @endif

            {{-- Actions --}}
            @if($reservasi->status === 'pending')
            <div class="flex gap-3 pt-4 border-t border-gray-100">
                <form action="{{ route('admin.reservasi.approve', $reservasi) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 rounded-xl transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-check"></i> Setujui Reservasi
                    </button>
                </form>
                <button onclick="document.getElementById('rejectSection').classList.toggle('hidden')"
                        class="flex-1 bg-red-50 hover:bg-red-100 text-red-700 font-medium py-3 rounded-xl transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-xmark"></i> Tolak Reservasi
                </button>
            </div>

            <div id="rejectSection" class="hidden">
                <form action="{{ route('admin.reservasi.reject', $reservasi) }}" method="POST">
                    @csrf
                    <textarea name="alasan_tolak" rows="3" required
                              class="w-full border border-red-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-red-500 text-sm mb-3"
                              placeholder="Tulis alasan penolakan..."></textarea>
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-3 rounded-xl transition">
                        Konfirmasi Tolak
                    </button>
                </form>
            </div>
            @endif

            <div class="flex gap-3 pt-2">
                <a href="{{ route('admin.reservasi.index') }}"
                   class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 rounded-xl transition text-center">
                    ← Kembali
                </a>
                <form action="{{ route('admin.reservasi.destroy', $reservasi) }}" method="POST"
                      onsubmit="return confirm('Hapus data reservasi ini?')" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full bg-red-50 hover:bg-red-100 text-red-700 font-medium py-3 rounded-xl transition">
                        <i class="fa-solid fa-trash mr-1"></i> Hapus Data
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
