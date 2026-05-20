@extends('layouts.admin')

@section('title', 'Kelola Reservasi')
@section('page-title', 'Kelola Reservasi')
@section('page-subtitle', 'Approve, reject, atau hapus reservasi')

@section('content')

{{-- Stats mini --}}
<div class="grid grid-cols-3 gap-4 mb-5">
    <a href="{{ route('admin.reservasi.index', ['status'=>'pending']) }}"
       class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 flex items-center gap-3 hover:bg-yellow-100 transition">
        <div class="w-10 h-10 bg-yellow-200 rounded-lg flex items-center justify-center">
            <i class="fa-solid fa-clock text-yellow-700"></i>
        </div>
        <div>
            <div class="text-2xl font-bold text-yellow-700">{{ $stats['pending'] }}</div>
            <div class="text-xs text-yellow-600">Pending</div>
        </div>
    </a>
    <a href="{{ route('admin.reservasi.index', ['status'=>'approved']) }}"
       class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-center gap-3 hover:bg-green-100 transition">
        <div class="w-10 h-10 bg-green-200 rounded-lg flex items-center justify-center">
            <i class="fa-solid fa-check text-green-700"></i>
        </div>
        <div>
            <div class="text-2xl font-bold text-green-700">{{ $stats['approved'] }}</div>
            <div class="text-xs text-green-600">Approved</div>
        </div>
    </a>
    <a href="{{ route('admin.reservasi.index', ['status'=>'rejected']) }}"
       class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-center gap-3 hover:bg-red-100 transition">
        <div class="w-10 h-10 bg-red-200 rounded-lg flex items-center justify-center">
            <i class="fa-solid fa-xmark text-red-700"></i>
        </div>
        <div>
            <div class="text-2xl font-bold text-red-700">{{ $stats['rejected'] }}</div>
            <div class="text-xs text-red-600">Rejected</div>
        </div>
    </a>
</div>

{{-- Filter --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-5">
    <form method="GET" class="flex flex-wrap gap-3 items-center">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari nama / no HP..."
               class="border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-green-500 w-48">
        <select name="status" class="border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-green-500">
            <option value="">Semua Status</option>
            <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Pending</option>
            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
        <select name="lapangan_id" class="border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-green-500">
            <option value="">Semua Lapangan</option>
            @foreach($lapangan as $lap)
                <option value="{{ $lap->id }}" {{ request('lapangan_id') == $lap->id ? 'selected' : '' }}>{{ $lap->nama }}</option>
            @endforeach
        </select>
        <input type="date" name="tanggal" value="{{ request('tanggal') }}"
               class="border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-green-500">
        <button type="submit" class="bg-gray-700 text-white px-4 py-2 rounded-xl text-sm hover:bg-gray-800 transition">
            <i class="fa-solid fa-magnifying-glass mr-1"></i> Filter
        </button>
        @if(request()->hasAny(['search','status','lapangan_id','tanggal']))
            <a href="{{ route('admin.reservasi.index') }}" class="text-sm text-gray-500 px-3 py-2 hover:text-gray-700">Reset</a>
        @endif
    </form>
</div>

{{-- Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="px-5 py-3 text-left">#</th>
                    <th class="px-5 py-3 text-left">Pemesan</th>
                    <th class="px-5 py-3 text-left">Lapangan</th>
                    <th class="px-5 py-3 text-left">Tanggal & Jam</th>
                    <th class="px-5 py-3 text-left">Durasi</th>
                    <th class="px-5 py-3 text-left">Total</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($reservasi as $res)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-4 text-gray-400 text-xs">{{ $reservasi->firstItem() + $loop->index }}</td>
                    <td class="px-5 py-4">
                        <p class="font-medium text-gray-800">{{ $res->nama_pemesan }}</p>
                        <p class="text-xs text-gray-400">{{ $res->no_hp }}</p>
                        @if($res->email)
                            <p class="text-xs text-gray-400">{{ $res->email }}</p>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-gray-700">{{ $res->lapangan->nama ?? '-' }}</td>
                    <td class="px-5 py-4">
                        <p class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($res->tanggal)->format('d M Y') }}</p>
                        <p class="text-xs text-gray-400">{{ substr($res->jam_mulai,0,5) }} – {{ substr($res->jam_selesai,0,5) }}</p>
                    </td>
                    <td class="px-5 py-4 text-gray-600">{{ $res->durasi_jam }} jam</td>
                    <td class="px-5 py-4 font-semibold text-green-700">{{ $res->total_format }}</td>
                    <td class="px-5 py-4">
                        @if($res->status === 'pending')
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-1 rounded-full">⏳ Pending</span>
                        @elseif($res->status === 'approved')
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-1 rounded-full">✅ Approved</span>
                        @else
                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-1 rounded-full">❌ Rejected</span>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="{{ route('admin.reservasi.show', $res) }}"
                               class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-100 transition" title="Detail">
                                <i class="fa-solid fa-eye text-xs"></i>
                            </a>
                            @if($res->status === 'pending')
                            <form action="{{ route('admin.reservasi.approve', $res) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                        class="w-8 h-8 bg-green-50 text-green-600 rounded-lg flex items-center justify-center hover:bg-green-100 transition" title="Approve">
                                    <i class="fa-solid fa-check text-xs"></i>
                                </button>
                            </form>
                            <button onclick="openRejectModal({{ $res->id }})"
                                    class="w-8 h-8 bg-red-50 text-red-600 rounded-lg flex items-center justify-center hover:bg-red-100 transition" title="Tolak">
                                <i class="fa-solid fa-xmark text-xs"></i>
                            </button>
                            @endif
                            <form action="{{ route('admin.reservasi.destroy', $res) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Hapus data reservasi ini?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="w-8 h-8 bg-gray-50 text-gray-500 rounded-lg flex items-center justify-center hover:bg-gray-100 transition" title="Hapus">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-5 py-12 text-center text-gray-400">
                        <i class="fa-solid fa-calendar-xmark text-4xl mb-3 block"></i>
                        Belum ada data reservasi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($reservasi->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $reservasi->links() }}
    </div>
    @endif
</div>

{{-- Modal Reject --}}
<div id="rejectModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
        <div class="bg-red-600 px-6 py-4 rounded-t-2xl text-white flex items-center gap-2">
            <i class="fa-solid fa-xmark-circle"></i>
            <h3 class="font-semibold">Tolak Reservasi</h3>
        </div>
        <form id="rejectForm" method="POST" class="p-6">
            @csrf
            <p class="text-gray-600 mb-4 text-sm">Masukkan alasan penolakan. Alasan ini akan dicatat dalam sistem.</p>
            <textarea name="alasan_tolak" rows="4" required
                      class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-red-500 text-sm"
                      placeholder="Contoh: Lapangan sedang dalam perbaikan / Jadwal bentrok..."></textarea>
            <div class="flex gap-3 mt-4">
                <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-medium py-3 rounded-xl transition">
                    Tolak Reservasi
                </button>
                <button type="button" onclick="closeRejectModal()"
                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 rounded-xl transition">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openRejectModal(id) {
    document.getElementById('rejectForm').action = `/admin/reservasi/${id}/reject`;
    document.getElementById('rejectModal').classList.remove('hidden');
}
function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}
// Close on backdrop click
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) closeRejectModal();
});
</script>
@endpush
