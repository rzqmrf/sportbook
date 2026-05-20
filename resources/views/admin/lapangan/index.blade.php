@extends('layouts.admin')

@section('title', 'Kelola Lapangan')
@section('page-title', 'Kelola Lapangan')
@section('page-subtitle', 'Daftar semua lapangan olahraga')

@section('content')

{{-- Filter & Tambah --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-5">
    <form method="GET" action="{{ route('admin.lapangan.index') }}" class="flex flex-wrap gap-2">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari nama / jenis..."
               class="border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-green-500 w-52">
        <select name="jenis" class="border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-green-500">
            <option value="">Semua Jenis</option>
            @foreach(['Futsal','Badminton','Basket','Voli','Tenis','Lainnya'] as $j)
                <option value="{{ $j }}" {{ request('jenis') === $j ? 'selected' : '' }}>{{ $j }}</option>
            @endforeach
        </select>
        <select name="status" class="border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-green-500">
            <option value="">Semua Status</option>
            <option value="aktif"    {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
        </select>
        <button type="submit" class="bg-gray-700 text-white px-4 py-2 rounded-xl text-sm hover:bg-gray-800 transition">
            <i class="fa-solid fa-magnifying-glass mr-1"></i> Cari
        </button>
        @if(request()->hasAny(['search','jenis','status']))
            <a href="{{ route('admin.lapangan.index') }}" class="text-sm text-gray-500 px-3 py-2 hover:text-gray-700">Reset</a>
        @endif
    </form>
    <a href="{{ route('admin.lapangan.create') }}"
       class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-xl text-sm font-medium transition flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Tambah Lapangan
    </a>
</div>

{{-- Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="px-5 py-3 text-left">Lapangan</th>
                    <th class="px-5 py-3 text-left">Jenis</th>
                    <th class="px-5 py-3 text-left">Harga/Jam</th>
                    <th class="px-5 py-3 text-left">Jam Operasional</th>
                    <th class="px-5 py-3 text-left">Reservasi</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($lapangan as $lap)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl overflow-hidden bg-green-100 flex-shrink-0">
                                @if($lap->foto)
                                    <img src="{{ asset('uploads/lapangan/'.$lap->foto) }}" alt="{{ $lap->nama }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="fa-solid fa-image text-green-400 text-xl"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $lap->nama }}</p>
                                <p class="text-xs text-gray-400 truncate max-w-xs">{{ Str::limit($lap->deskripsi, 40) }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        <span class="bg-blue-100 text-blue-700 text-xs font-medium px-2.5 py-1 rounded-full">{{ $lap->jenis }}</span>
                    </td>
                    <td class="px-5 py-4 font-semibold text-green-700">{{ $lap->harga_format }}</td>
                    <td class="px-5 py-4 text-gray-600">{{ substr($lap->jam_buka,0,5) }} – {{ substr($lap->jam_tutup,0,5) }}</td>
                    <td class="px-5 py-4">
                        <span class="font-semibold text-gray-700">{{ $lap->reservasi_count }}</span>
                        <span class="text-gray-400 text-xs">booking</span>
                    </td>
                    <td class="px-5 py-4">
                        <form action="{{ route('admin.lapangan.toggle', $lap) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                    class="{{ $lap->status === 'aktif' ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }} text-xs font-medium px-3 py-1.5 rounded-full transition cursor-pointer">
                                {{ $lap->status === 'aktif' ? '✅ Aktif' : '⛔ Non-aktif' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.lapangan.show', $lap) }}"
                               class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-100 transition" title="Detail">
                                <i class="fa-solid fa-eye text-xs"></i>
                            </a>
                            <a href="{{ route('admin.lapangan.edit', $lap) }}"
                               class="w-8 h-8 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center hover:bg-amber-100 transition" title="Edit">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </a>
                            <form action="{{ route('admin.lapangan.destroy', $lap) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Hapus lapangan {{ $lap->nama }}? Semua data reservasi terkait juga akan terhapus!')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="w-8 h-8 bg-red-50 text-red-600 rounded-lg flex items-center justify-center hover:bg-red-100 transition" title="Hapus">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                        <i class="fa-solid fa-circle-info text-3xl mb-3 block"></i>
                        Belum ada lapangan. <a href="{{ route('admin.lapangan.create') }}" class="text-green-600 hover:underline">Tambah sekarang</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($lapangan->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $lapangan->links() }}
    </div>
    @endif
</div>
@endsection
