@extends('layouts.landing')

@section('title', 'Reservasi Lapangan — SportBook')

@section('content')
<div class="pt-20 min-h-screen bg-gray-50">
    <div class="max-w-3xl mx-auto px-4 py-12">
        <!-- Header -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center gap-2 bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-medium mb-4">
                <i class="fa-solid fa-calendar-plus"></i> Form Reservasi
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Booking Lapangan Olahraga</h1>
            <p class="text-gray-500 mt-2">Isi form di bawah ini untuk memesan lapangan. Admin akan segera mengkonfirmasi.</p>
        </div>

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-5 py-4 rounded-xl flex items-start gap-3 mb-6">
            <i class="fa-solid fa-circle-check text-green-500 mt-0.5"></i>
            <div>
                <div class="font-semibold">Reservasi Terkirim!</div>
                <div class="text-sm mt-1">{{ session('success') }}</div>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-xl flex items-start gap-3 mb-6">
            <i class="fa-solid fa-circle-xmark text-red-500 mt-0.5"></i>
            <div>{{ session('error') }}</div>
        </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4 text-white">
                <h2 class="font-semibold flex items-center gap-2">
                    <i class="fa-solid fa-clipboard-list"></i> Detail Pemesanan
                </h2>
            </div>

            <form action="{{ route('reservasi.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <!-- Pilih Lapangan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Lapangan <span class="text-red-500">*</span>
                    </label>
                    <select name="lapangan_id" id="lapangan_id" required
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-transparent @error('lapangan_id') border-red-400 @enderror"
                            onchange="updateHarga()">
                        <option value="">-- Pilih Lapangan --</option>
                        @foreach($lapangan as $lap)
                            <option value="{{ $lap->id }}"
                                    data-harga="{{ $lap->harga_per_jam }}"
                                    data-jam-buka="{{ substr($lap->jam_buka,0,5) }}"
                                    data-jam-tutup="{{ substr($lap->jam_tutup,0,5) }}"
                                    {{ (request('lapangan') == $lap->id || old('lapangan_id') == $lap->id) ? 'selected' : '' }}>
                                {{ $lap->nama }} ({{ $lap->jenis }}) — Rp {{ number_format($lap->harga_per_jam,0,',','.') }}/jam
                            </option>
                        @endforeach
                    </select>
                    @error('lapangan_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Info harga -->
                <div id="info-lapangan" class="hidden bg-green-50 border border-green-200 rounded-xl p-4">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-circle-info text-green-500"></i>
                        <div class="text-sm text-green-800">
                            Harga: <strong id="harga-display">-</strong>/jam &nbsp;|&nbsp;
                            Jam operasional: <strong id="jam-display">-</strong>
                        </div>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_pemesan" value="{{ old('nama_pemesan') }}" required
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 @error('nama_pemesan') border-red-400 @enderror"
                               placeholder="Nama lengkap kamu">
                        @error('nama_pemesan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <!-- No HP -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nomor HP <span class="text-red-500">*</span></label>
                        <input type="text" name="no_hp" value="{{ old('no_hp') }}" required
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 @error('no_hp') border-red-400 @enderror"
                               placeholder="0812-xxxx-xxxx">
                        @error('no_hp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-gray-400 text-xs">(opsional)</span></label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500"
                           placeholder="email@contoh.com">
                </div>

                <div class="grid md:grid-cols-3 gap-6">
                    <!-- Tanggal -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required
                               min="{{ date('Y-m-d') }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 @error('tanggal') border-red-400 @enderror">
                        @error('tanggal') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <!-- Jam Mulai -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jam Mulai <span class="text-red-500">*</span></label>
                        <input type="time" name="jam_mulai" value="{{ old('jam_mulai', '08:00') }}" required
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500"
                               onchange="hitungTotal()">
                    </div>
                    <!-- Durasi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Durasi (jam) <span class="text-red-500">*</span></label>
                        <select name="durasi_jam" required
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500"
                                onchange="hitungTotal()">
                            @for($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}" {{ old('durasi_jam', 1) == $i ? 'selected' : '' }}>{{ $i }} Jam</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <!-- Catatan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan <span class="text-gray-400 text-xs">(opsional)</span></label>
                    <textarea name="catatan" rows="3"
                              class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500"
                              placeholder="Tambahan informasi jika diperlukan...">{{ old('catatan') }}</textarea>
                </div>

                <!-- Total harga preview -->
                <div id="total-preview" class="hidden bg-amber-50 border border-amber-200 rounded-xl p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm text-gray-600">Estimasi Total Bayar</div>
                            <div class="text-2xl font-bold text-amber-600 mt-1" id="total-display">Rp 0</div>
                        </div>
                        <div class="text-right text-sm text-gray-500">
                            <div id="jam-selesai-display">—</div>
                            <div class="text-xs text-gray-400 mt-1">Jam selesai</div>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-3">
                        <i class="fa-solid fa-circle-info mr-1"></i>
                        Pembayaran dilakukan di tempat setelah konfirmasi admin.
                    </p>
                </div>

                <button type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-xl transition flex items-center justify-center gap-2 text-lg">
                    <i class="fa-solid fa-paper-plane"></i> Kirim Reservasi
                </button>
            </form>
        </div>

        <p class="text-center text-gray-400 text-sm mt-6">
            Reservasi akan dikonfirmasi oleh admin dalam 1×24 jam. Pertanyaan? Hubungi kami di 0812-3456-7890.
        </p>
    </div>
</div>
@endsection

@push('scripts')
<script>
const hargaMap = {};
@foreach($lapangan as $lap)
    hargaMap[{{ $lap->id }}] = {
        harga: {{ $lap->harga_per_jam }},
        jamBuka: '{{ substr($lap->jam_buka,0,5) }}',
        jamTutup: '{{ substr($lap->jam_tutup,0,5) }}',
    };
@endforeach

function updateHarga() {
    const sel = document.getElementById('lapangan_id');
    const id  = sel.value;
    const info = document.getElementById('info-lapangan');
    if (id && hargaMap[id]) {
        const d = hargaMap[id];
        document.getElementById('harga-display').textContent = 'Rp ' + d.harga.toLocaleString('id-ID');
        document.getElementById('jam-display').textContent   = d.jamBuka + ' – ' + d.jamTutup;
        info.classList.remove('hidden');
    } else {
        info.classList.add('hidden');
    }
    hitungTotal();
}

function hitungTotal() {
    const selLap   = document.getElementById('lapangan_id');
    const jamMulai = document.querySelector('[name="jam_mulai"]').value;
    const durasi   = parseInt(document.querySelector('[name="durasi_jam"]').value);
    const id       = selLap.value;
    const preview  = document.getElementById('total-preview');

    if (!id || !jamMulai || !durasi) { preview.classList.add('hidden'); return; }

    const harga = hargaMap[id]?.harga || 0;
    const total = harga * durasi;
    const [h, m] = jamMulai.split(':').map(Number);
    const selesaiMin = h * 60 + m + durasi * 60;
    const selesaiH   = Math.floor(selesaiMin / 60) % 24;
    const selesaiM   = selesaiMin % 60;
    const selesaiStr = String(selesaiH).padStart(2,'0') + ':' + String(selesaiM).padStart(2,'0');

    document.getElementById('total-display').textContent      = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('jam-selesai-display').textContent = jamMulai + ' – ' + selesaiStr;
    preview.classList.remove('hidden');
}

// Init if lapangan preselected
document.addEventListener('DOMContentLoaded', () => { updateHarga(); });
</script>
@endpush
