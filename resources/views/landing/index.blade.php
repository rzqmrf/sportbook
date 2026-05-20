@extends('layouts.landing')

@section('title', 'E-ReservLap - Reservasi Lapangan Olahraga')

@section('content')
<!-- HERO -->
<section class="gradient-hero min-h-screen flex items-center pt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full py-20">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="text-white">
                <span class="inline-block bg-white/10 backdrop-blur text-green-300 text-sm font-medium px-4 py-1.5 rounded-full mb-6">
                    🏟️ Platform Reservasi Terpercaya
                </span>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight mb-6">
                    Booking Lapangan <span class="text-amber-400">Olahraga</span> Jadi Lebih Mudah
                </h1>
                <p class="text-green-100 text-lg mb-8 leading-relaxed">
                    Reservasi lapangan futsal, badminton, basket, voli, dan lebih banyak lagi — kapan saja, di mana saja. Proses cepat, konfirmasi transparan.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('reservasi') }}"
                       class="bg-amber-400 hover:bg-amber-500 text-gray-900 font-bold px-8 py-4 rounded-xl transition flex items-center gap-2 shadow-lg">
                        <i class="fa-solid fa-calendar-plus"></i> Reservasi Sekarang
                    </a>
                    <a href="#lapangan"
                       class="bg-white/10 hover:bg-white/20 text-white font-medium px-8 py-4 rounded-xl transition flex items-center gap-2 border border-white/20">
                        <i class="fa-solid fa-eye"></i> Lihat Lapangan
                    </a>
                </div>
                <!-- Stats -->
                <div class="grid grid-cols-3 gap-6 mt-12 pt-8 border-t border-white/20">
                    <div>
                        <div class="text-3xl font-bold text-amber-400">{{ $lapangan->count() }}+</div>
                        <div class="text-green-200 text-sm">Lapangan Aktif</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-amber-400">500+</div>
                        <div class="text-green-200 text-sm">Member</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-amber-400">98%</div>
                        <div class="text-green-200 text-sm">Kepuasan</div>
                    </div>
                </div>
            </div>
            <!-- Hero illustration -->
            <div class="hidden md:flex justify-center">
                <div class="float-anim">
                    <div class="relative w-80 h-80">
                        <div class="w-80 h-80 bg-white/10 rounded-full flex items-center justify-center">
                            <div class="w-64 h-64 bg-white/10 rounded-full flex items-center justify-center">
                                <i class="fa-solid fa-futbol text-9xl text-white/80"></i>
                            </div>
                        </div>
                        <div class="absolute top-4 right-0 bg-amber-400 text-gray-900 rounded-2xl px-4 py-2 shadow-xl">
                            <div class="text-xs font-bold">⚡ Instant Booking</div>
                        </div>
                        <div class="absolute bottom-8 left-0 bg-white rounded-2xl px-4 py-2 shadow-xl">
                            <div class="text-xs font-bold text-green-700">✅ Tersedia Hari Ini</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ABOUT -->
<section id="about" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 gap-16 items-center">
            <div>
                <span class="text-green-600 font-semibold text-sm uppercase tracking-wide">Tentang Kami</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-2 mb-6 text-gray-900">Kenapa Pilih <span class="text-green-600">E-ReservLap</span>?</h2>
                <p class="text-gray-600 mb-6 leading-relaxed">
                    E-ReservLap hadir untuk mempermudah proses reservasi lapangan olahraga. Kami menyediakan fasilitas olahraga berkualitas dengan sistem pemesanan yang modern dan transparan.
                </p>
                <p class="text-gray-600 mb-8 leading-relaxed">
                    Tim kami berkomitmen memberikan pengalaman terbaik, mulai dari proses booking yang cepat hingga lapangan yang terawat dan bersih setiap harinya.
                </p>
                <div class="space-y-4">
                    @foreach([['fa-bolt','Proses cepat & mudah','Reservasi selesai dalam hitungan menit'],['fa-shield-halved','Terpercaya & aman','Data dan transaksi terjamin keamanannya'],['fa-headset','Support 7 hari','Tim kami siap membantu kapan saja']] as $item)
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid {{ $item[0] }} text-green-600"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800">{{ $item[1] }}</div>
                            <div class="text-sm text-gray-500">{{ $item[2] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                @foreach([['🏟️','Lapangan Luas','Standar nasional'],['💡','Pencahayaan LED','Terang & hemat energi'],['🚿','Fasilitas Mandi','Loker & kamar mandi bersih'],['🅿️','Parkir Luas','Gratis untuk semua pengunjung']] as $f)
                <div class="bg-gray-50 rounded-2xl p-6 card-hover">
                    <div class="text-3xl mb-3">{{ $f[0] }}</div>
                    <div class="font-semibold text-gray-800 mb-1">{{ $f[1] }}</div>
                    <div class="text-sm text-gray-500">{{ $f[2] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- FITUR -->
<section id="fitur" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <span class="text-green-600 font-semibold text-sm uppercase tracking-wide">Fitur Unggulan</span>
            <h2 class="text-3xl md:text-4xl font-bold mt-2 text-gray-900">Semua yang Kamu Butuhkan</h2>
            <p class="text-gray-500 mt-3 max-w-xl mx-auto">Nikmati kemudahan reservasi lapangan olahraga dengan berbagai fitur modern yang kami sediakan.</p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            @foreach([
                ['fa-calendar-alt','text-blue-600','bg-blue-50','Booking Online','Reservasi lapangan kapan saja dan di mana saja hanya dalam beberapa langkah mudah.'],
                ['fa-check-circle','text-green-600','bg-green-50','Konfirmasi Real-time','Admin akan segera mengkonfirmasi reservasimu. Status booking selalu up-to-date.'],
                ['fa-clock','text-purple-600','bg-purple-50','Pilih Jam Fleksibel','Tentukan jam dan durasi sewa sesuai kebutuhanmu dari pagi hingga malam.'],
                ['fa-futbol','text-amber-600','bg-amber-50','Berbagai Olahraga','Tersedia lapangan futsal, badminton, basket, voli, tenis, dan masih banyak lagi.'],
                ['fa-dollar-sign','text-red-600','bg-red-50','Harga Transparan','Lihat harga per jam sebelum booking. Tidak ada biaya tersembunyi.'],
                ['fa-shield-alt','text-indigo-600','bg-indigo-50','Data Aman','Informasi pribadi kamu tersimpan dengan aman menggunakan enkripsi terkini.'],
            ] as $f)
            <div class="bg-white rounded-2xl p-7 card-hover shadow-sm border border-gray-100">
                <div class="w-12 h-12 {{ $f[2] }} rounded-xl flex items-center justify-center mb-4">
                    <i class="fa-solid {{ $f[0] }} {{ $f[1] }} text-xl"></i>
                </div>
                <h3 class="font-bold text-gray-800 mb-2">{{ $f[3] }}</h3>
                <p class="text-gray-500 text-sm leading-relaxed">{{ $f[4] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- LAPANGAN -->
<section id="lapangan" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <span class="text-green-600 font-semibold text-sm uppercase tracking-wide">Fasilitas Kami</span>
            <h2 class="text-3xl md:text-4xl font-bold mt-2 text-gray-900">Pilihan Lapangan Tersedia</h2>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($lapangan as $lap)
            <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 card-hover">
                <div class="relative h-48 bg-gradient-to-br from-green-400 to-green-700 overflow-hidden">
                    @if($lap->foto)
                        <img src="{{ asset('uploads/lapangan/'.$lap->foto) }}" alt="{{ $lap->nama }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            @php
                                $icons = ['Futsal'=>'fa-futbol','Badminton'=>'fa-shuttlecock','Basket'=>'fa-basketball','Voli'=>'fa-volleyball','Tenis'=>'fa-tennis-ball','Lainnya'=>'fa-trophy'];
                                $icon = $icons[$lap->jenis] ?? 'fa-trophy';
                            @endphp
                            <i class="fa-solid {{ $icon }} text-6xl text-white/40"></i>
                        </div>
                    @endif
                    <div class="absolute top-3 left-3">
                        <span class="bg-white/90 text-green-700 text-xs font-bold px-3 py-1 rounded-full">{{ $lap->jenis }}</span>
                    </div>
                    <div class="absolute top-3 right-3">
                        <span class="bg-green-600 text-white text-xs font-bold px-3 py-1 rounded-full">Tersedia</span>
                    </div>
                </div>
                <div class="p-5">
                    <h3 class="font-bold text-gray-800 text-lg mb-1">{{ $lap->nama }}</h3>
                    <p class="text-gray-500 text-sm mb-3 line-clamp-2">{{ $lap->deskripsi }}</p>
                    <div class="flex items-center gap-4 text-sm text-gray-500 mb-4">
                        <span><i class="fa-regular fa-clock mr-1 text-green-500"></i>{{ substr($lap->jam_buka,0,5) }} – {{ substr($lap->jam_tutup,0,5) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs text-gray-400">Mulai dari</span>
                            <div class="text-xl font-bold text-green-600">{{ $lap->harga_format }}<span class="text-sm text-gray-400 font-normal">/jam</span></div>
                        </div>
                        <a href="{{ route('reservasi') }}?lapangan={{ $lap->id }}"
                           class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition">
                            Pesan
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-12 text-gray-400">
                <i class="fa-solid fa-triangle-exclamation text-4xl mb-3"></i>
                <p>Belum ada lapangan tersedia saat ini.</p>
            </div>
            @endforelse
        </div>
        <div class="text-center mt-10">
            <a href="{{ route('reservasi') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold px-10 py-4 rounded-xl transition inline-flex items-center gap-2">
                <i class="fa-solid fa-calendar-plus"></i> Reservasi Sekarang
            </a>
        </div>
    </div>
</section>

<!-- CTA SECTION -->
<section class="gradient-hero py-20">
    <div class="max-w-4xl mx-auto px-4 text-center text-white">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Siap Berolahraga? 🏃‍♂️</h2>
        <p class="text-green-100 mb-8 text-lg">Jangan tunda lagi! Book lapangan favoritmu sekarang dan nikmati sesi olahraga yang menyenangkan.</p>
        <a href="{{ route('reservasi') }}" class="bg-amber-400 hover:bg-amber-500 text-gray-900 font-bold px-10 py-4 rounded-xl transition inline-flex items-center gap-2 text-lg shadow-xl">
            <i class="fa-solid fa-bolt"></i> Reservasi Sekarang — Gratis!
        </a>
    </div>
</section>
@endsection
