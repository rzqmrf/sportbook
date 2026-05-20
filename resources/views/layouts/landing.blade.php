<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'E-ReservLap - Reservasi Lapangan Olahraga')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary:  { DEFAULT: '#16a34a', dark: '#15803d', light: '#dcfce7' },
                        accent:   '#f59e0b',
                    }
                }
            }
        }
    </script>
    <style>
        html { scroll-behavior: smooth; }
        .gradient-hero { background: linear-gradient(135deg, #064e3b 0%, #065f46 40%, #047857 100%); }
        .card-hover { transition: transform .3s, box-shadow .3s; }
        .card-hover:hover { transform: translateY(-6px); box-shadow: 0 20px 40px rgba(0,0,0,.15); }
        .nav-link { position: relative; }
        .nav-link::after { content:''; position:absolute; bottom:-2px; left:0; width:0; height:2px; background:#16a34a; transition: width .3s; }
        .nav-link:hover::after { width:100%; }
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-12px)} }
        .float-anim { animation: float 4s ease-in-out infinite; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans text-gray-800">

<!-- Navbar -->
<nav class="fixed top-0 w-full bg-white/95 backdrop-blur-sm shadow-sm z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <a href="{{ route('landing') }}" class="flex items-center gap-2">
                <div class="w-9 h-9 bg-green-600 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-futbol text-white text-lg"></i>
                </div>
                <span class="text-xl font-bold text-green-700">E-Reserv<span class="text-amber-500">Lap</span></span>
            </a>
            <div class="hidden md:flex items-center gap-6">
                <a href="#about"  class="nav-link text-gray-600 hover:text-green-700 text-sm font-medium">Tentang</a>
                <a href="#fitur"  class="nav-link text-gray-600 hover:text-green-700 text-sm font-medium">Fitur</a>
                <a href="#lapangan" class="nav-link text-gray-600 hover:text-green-700 text-sm font-medium">Lapangan</a>
                <a href="#kontak" class="nav-link text-gray-600 hover:text-green-700 text-sm font-medium">Kontak</a>
                <a href="{{ route('reservasi') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition">Reservasi Sekarang</a>
                <a href="{{ route('admin.login') }}" class="border border-green-600 text-green-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-50 transition">
                    <i class="fa-solid fa-lock mr-1"></i> Login Admin
                </a>
            </div>
            <!-- Mobile menu btn -->
            <button onclick="document.getElementById('mobileMenu').classList.toggle('hidden')" class="md:hidden text-gray-600">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>
        </div>
    </div>
    <!-- Mobile menu -->
    <div id="mobileMenu" class="hidden md:hidden bg-white border-t px-4 py-3 space-y-2">
        <a href="#about" class="block text-gray-600 py-2">Tentang</a>
        <a href="#fitur" class="block text-gray-600 py-2">Fitur</a>
        <a href="#lapangan" class="block text-gray-600 py-2">Lapangan</a>
        <a href="#kontak" class="block text-gray-600 py-2">Kontak</a>
        <a href="{{ route('reservasi') }}" class="block bg-green-600 text-white text-center py-2 rounded-lg mt-2">Reservasi</a>
        <a href="{{ route('admin.login') }}" class="block border border-green-600 text-green-600 text-center py-2 rounded-lg">Login Admin</a>
    </div>
</nav>

@yield('content')

<!-- Footer -->
<footer class="bg-gray-900 text-gray-300 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-9 h-9 bg-green-600 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-futbol text-white"></i>
                    </div>
                    <span class="text-xl font-bold text-white">E-Reserv<span class="text-amber-400">Lap</span></span>
                </div>
                <p class="text-sm leading-relaxed">Platform reservasi lapangan olahraga terpercaya. Mudah, cepat, dan transparan.</p>
            </div>
            <div>
                <h4 class="font-semibold text-white mb-3">Navigasi</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#about" class="hover:text-green-400 transition">Tentang Kami</a></li>
                    <li><a href="#fitur" class="hover:text-green-400 transition">Fitur</a></li>
                    <li><a href="#lapangan" class="hover:text-green-400 transition">Lapangan</a></li>
                    <li><a href="{{ route('reservasi') }}" class="hover:text-green-400 transition">Reservasi</a></li>
                </ul>
            </div>
            <div id="kontak">
                <h4 class="font-semibold text-white mb-3">Kontak</h4>
                <ul class="space-y-2 text-sm">
                    <li class="flex items-center gap-2"><i class="fa-solid fa-location-dot text-green-400 w-4"></i> Jl. Olahraga No. 1, Kota</li>
                    <li class="flex items-center gap-2"><i class="fa-solid fa-phone text-green-400 w-4"></i> 0812-3456-7890</li>
                    <li class="flex items-center gap-2"><i class="fa-solid fa-envelope text-green-400 w-4"></i> info@E-ReservLap.com</li>
                    <li class="flex items-center gap-2"><i class="fa-solid fa-clock text-green-400 w-4"></i> Buka 06.00 – 22.00 WIB</li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-700 pt-6 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} E-ReservLap. Dibuat dengan <i class="fa-solid fa-heart text-red-400"></i> menggunakan Laravel.
        </div>
    </div>
</footer>

@stack('scripts')
</body>
</html>
