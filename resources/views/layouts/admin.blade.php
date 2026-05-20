<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — SportBook</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        .sidebar-link { transition: all .2s; }
        .sidebar-link:hover, .sidebar-link.active { background: rgba(255,255,255,.1); border-left: 3px solid #4ade80; padding-left: 13px; }
        .sidebar-link { border-left: 3px solid transparent; }
        .badge-pending  { @apply bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full; }
        .badge-approved { @apply bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full; }
        .badge-rejected { @apply bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full; }
        .stat-card { transition: transform .2s; }
        .stat-card:hover { transform: translateY(-3px); }
    </style>
</head>
<body class="bg-gray-100 font-sans">

<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside class="w-64 bg-gradient-to-b from-green-800 to-green-900 text-white flex flex-col flex-shrink-0">
        <!-- Logo -->
        <div class="p-5 border-b border-green-700">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <div class="w-9 h-9 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-futbol text-white"></i>
                </div>
                <div>
                    <div class="font-bold text-lg leading-none">E-ReservLap</div>
                    <div class="text-xs text-green-300">Admin Panel</div>
                </div>
            </a>
        </div>

        <!-- Nav -->
        <nav class="flex-1 p-4 space-y-1">
            <div class="text-xs text-green-400 uppercase tracking-wider mb-3 px-2">Menu Utama</div>

            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.dashboard') ? 'active bg-white/10' : '' }}">
                <i class="fa-solid fa-chart-line w-4"></i> Dashboard
            </a>

            <a href="{{ route('admin.lapangan.index') }}"
               class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.lapangan*') ? 'active bg-white/10' : '' }}">
                <i class="fa-solid fa-table-tennis-paddle-ball w-4"></i> Kelola Lapangan
            </a>

            <a href="{{ route('admin.reservasi.index') }}"
               class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.reservasi*') ? 'active bg-white/10' : '' }}">
                <i class="fa-solid fa-calendar-check w-4"></i> Kelola Reservasi
                @php $pending = \App\Models\Reservasi::where('status','pending')->count(); @endphp
                @if($pending > 0)
                    <span class="ml-auto bg-amber-400 text-gray-900 text-xs rounded-full px-2 py-0.5 font-bold">{{ $pending }}</span>
                @endif
            </a>

            <div class="border-t border-green-700 my-3"></div>
            <a href="{{ route('landing') }}" target="_blank"
               class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-green-300">
                <i class="fa-solid fa-arrow-up-right-from-square w-4"></i> Lihat Website
            </a>
        </nav>

        <!-- User info -->
        <div class="p-4 border-t border-green-700">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-user text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium truncate">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-green-300 truncate">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-red-300 hover:bg-white/10 rounded-lg transition">
                    <i class="fa-solid fa-right-from-bracket w-4"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Topbar -->
        <header class="bg-white shadow-sm px-6 py-3 flex items-center justify-between flex-shrink-0">
            <div>
                <h1 class="text-lg font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                <p class="text-xs text-gray-500">@yield('page-subtitle', '')</p>
            </div>
            <div class="flex items-center gap-3 text-sm text-gray-500">
                <i class="fa-regular fa-calendar"></i>
                {{ now()->translatedFormat('l, d F Y') }}
            </div>
        </header>

        <!-- Flash messages -->
        <div class="px-6 pt-4">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center gap-2 mb-0">
                    <i class="fa-solid fa-circle-check text-green-500"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center gap-2 mb-0">
                    <i class="fa-solid fa-circle-xmark text-red-500"></i>
                    {{ session('error') }}
                </div>
            @endif
        </div>

        <!-- Page content -->
        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
