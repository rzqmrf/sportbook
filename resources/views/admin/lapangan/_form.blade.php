{{-- resources/views/admin/lapangan/_form.blade.php --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- KOLOM KIRI (2/3): Informasi Utama --}}
    <div class="xl:col-span-2 space-y-5">

        {{-- Card: Informasi Dasar --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-green-50 text-green-600">
                    <i class="fa-solid fa-clipboard-list text-sm"></i>
                </span>
                <h3 class="text-base font-semibold text-gray-800">Informasi Dasar</h3>
            </div>
            <div class="p-6 space-y-5">

                {{-- Nama Lapangan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Nama Lapangan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama"
                           value="{{ old('nama', $lapangan->nama ?? '') }}"
                           required
                           placeholder="Contoh: Lapangan Futsal Premium A"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 focus:bg-white transition @error('nama') border-red-400 bg-red-50 @enderror">
                    @error('nama')
                        <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Jenis & Harga --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Jenis Lapangan <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="jenis" required
                                    class="w-full appearance-none px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 focus:bg-white transition cursor-pointer">
                                <option value="">Pilih Jenis</option>
                                @foreach($jenisOptions as $j)
                                    <option value="{{ $j }}" {{ old('jenis', $lapangan->jenis ?? '') === $j ? 'selected' : '' }}>
                                        {{ $j }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Harga per Jam <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-medium select-none">Rp</span>
                            <input type="number" name="harga_per_jam"
                                   value="{{ old('harga_per_jam', $lapangan->harga_per_jam ?? '') }}"
                                   required
                                   placeholder="100000"
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 focus:bg-white transition">
                        </div>
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi</label>
                    <textarea name="deskripsi" rows="4"
                              placeholder="Ceritakan keunggulan dan fasilitas lapangan..."
                              class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 focus:bg-white transition resize-none">{{ old('deskripsi', $lapangan->deskripsi ?? '') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Card: Waktu Operasional --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-blue-50 text-blue-600">
                    <i class="fa-solid fa-clock text-sm"></i>
                </span>
                <h3 class="text-base font-semibold text-gray-800">Waktu Operasional</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Jam Buka</label>
                        <input type="time" name="jam_buka"
                               value="{{ old('jam_buka', isset($lapangan) ? substr($lapangan->jam_buka,0,5) : '07:00') }}"
                               required
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Jam Tutup</label>
                        <input type="time" name="jam_tutup"
                               value="{{ old('jam_tutup', isset($lapangan) ? substr($lapangan->jam_tutup,0,5) : '22:00') }}"
                               required
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN (1/3): Status & Foto --}}
    <div class="space-y-5">

        {{-- Card: Status --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-amber-50 text-amber-600">
                    <i class="fa-solid fa-toggle-on text-sm"></i>
                </span>
                <h3 class="text-base font-semibold text-gray-800">Status</h3>
            </div>
            <div class="p-6">
                <div class="flex gap-3">
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="status" value="aktif" class="sr-only peer"
                               {{ old('status', $lapangan->status ?? 'aktif') === 'aktif' ? 'checked' : '' }}>
                        <span class="flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl border-2 border-gray-200 text-sm font-medium text-gray-500 transition-all
                                     peer-checked:border-green-500 peer-checked:bg-green-50 peer-checked:text-green-700">
                            <i class="fa-solid fa-circle-check text-xs"></i> Aktif
                        </span>
                    </label>
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="status" value="nonaktif" class="sr-only peer"
                               {{ old('status', $lapangan->status ?? 'aktif') === 'nonaktif' ? 'checked' : '' }}>
                        <span class="flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl border-2 border-gray-200 text-sm font-medium text-gray-500 transition-all
                                     peer-checked:border-red-400 peer-checked:bg-red-50 peer-checked:text-red-600">
                            <i class="fa-solid fa-circle-xmark text-xs"></i> Nonaktif
                        </span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Card: Foto --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-purple-50 text-purple-600">
                    <i class="fa-solid fa-image text-sm"></i>
                </span>
                <h3 class="text-base font-semibold text-gray-800">Foto Lapangan</h3>
            </div>
            <div class="p-6">
                <div id="upload-area"
                     class="relative rounded-xl border-2 border-dashed border-gray-200 hover:border-green-400 transition-colors duration-200 cursor-pointer overflow-hidden"
                     onclick="document.getElementById('foto-input').click()">

                    {{-- Input file tersembunyi --}}
                    <input id="foto-input" type="file" name="foto" accept="image/*"
                           class="hidden" onchange="previewFoto(this)">

                    {{-- Placeholder --}}
                    <div id="upload-placeholder"
                         class="{{ isset($lapangan) && $lapangan->foto ? 'hidden' : '' }} flex flex-col items-center justify-center py-10 px-4 text-center">
                        <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mb-3 text-gray-400">
                            <i class="fa-solid fa-cloud-arrow-up text-xl"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-600">Klik untuk upload foto</p>
                        <p class="text-xs text-gray-400 mt-1">JPG, PNG — Maks. 2MB</p>
                    </div>

                    {{-- Preview --}}
                    <div id="preview-wrapper" class="{{ isset($lapangan) && $lapangan->foto ? '' : 'hidden' }}">
                        <img id="foto-preview-element"
                             src="{{ isset($lapangan) && $lapangan->foto ? asset('uploads/lapangan/'.$lapangan->foto) : '#' }}"
                             class="w-full h-48 object-cover">
                        <div class="absolute inset-0 bg-black/0 hover:bg-black/20 transition-colors flex items-center justify-center">
                            <span class="opacity-0 hover:opacity-100 text-white text-xs font-medium bg-black/50 px-3 py-1.5 rounded-lg">
                                Ganti foto
                            </span>
                        </div>
                    </div>
                </div>

                @if(isset($lapangan) && $lapangan->foto)
                    <p class="mt-2 text-xs text-gray-400 text-center">Biarkan kosong jika tidak ingin mengubah foto</p>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function previewFoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('foto-preview-element').src = e.target.result;
            document.getElementById('preview-wrapper').classList.remove('hidden');
            document.getElementById('upload-placeholder').classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
