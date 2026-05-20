<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — E-ReservLap</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    
    <!-- ChartJS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            DEFAULT: '#059669', // Emerald 600
                            dark: '#047857', // Emerald 700
                            light: '#e6f4ea',
                        },
                        royal: {
                            DEFAULT: '#2563eb',
                            dark: '#1d4ed8',
                        }
                    },
                    borderRadius: {
                        '2.5xl': '1.25rem',
                        '3xl': '1.5rem',
                    }
                }
            }
        }
    </script>
    
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(156, 163, 175, 0.2);
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(156, 163, 175, 0.4);
        }
    </style>
</head>
<body class="bg-slate-50/50 font-sans text-slate-800 antialiased min-h-screen flex">

    <!-- Mobile Sidebar Backdrop (Overlay) -->
    <div id="sidebarBackdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 hidden lg:hidden transition-opacity duration-300 opacity-0"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-slate-950 text-white flex flex-col flex-shrink-0 z-50 transform -translate-x-full lg:translate-x-0 lg:static transition-transform duration-300 ease-in-out border-r border-slate-900">
        <!-- Logo Header -->
        <div class="p-6 border-b border-slate-900 flex items-center justify-between">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-tr from-primary to-royal rounded-xl flex items-center justify-center shadow-lg shadow-primary/20">
                    <i class="fa-solid fa-gauge-high text-white text-base"></i>
                </div>
                <div>
                    <div class="font-black text-sm tracking-tight text-white">E-Reserv<span class="text-primary">Lap</span></div>
                    <div class="text-[10px] text-slate-400 font-extrabold uppercase tracking-widest mt-0.5">Admin Console</div>
                </div>
            </a>
            <button onclick="toggleSidebar()" class="lg:hidden w-8 h-8 flex items-center justify-center rounded-lg hover:bg-white/10 text-slate-400 hover:text-white transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Sidebar Navigation Links -->
        <nav class="flex-1 px-4 py-6 space-y-6 overflow-y-auto custom-scrollbar">
            <div>
                <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest px-3.5 mb-3">Menu Utama</div>
                <div class="space-y-1.5">
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold transition duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-slate-400 hover:text-slate-200 hover:bg-white/5' }}">
                        <i class="fa-solid fa-chart-line text-sm"></i> Dashboard
                    </a>
                    
                    <a href="{{ route('admin.lapangan.index') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold transition duration-200 {{ request()->routeIs('admin.lapangan*') ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-slate-400 hover:text-slate-200 hover:bg-white/5' }}">
                        <i class="fa-solid fa-table-tennis-paddle-ball text-sm"></i> Kelola Lapangan
                    </a>

                    <a href="{{ route('admin.reservasi.index') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold transition duration-200 {{ request()->routeIs('admin.reservasi*') ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-slate-400 hover:text-slate-200 hover:bg-white/5' }}">
                        <i class="fa-solid fa-calendar-check text-sm"></i> Kelola Reservasi
                        @php $pending = \App\Models\Reservasi::where('status','pending')->count(); @endphp
                        @if($pending > 0)
                            <span class="ml-auto bg-amber-500 text-slate-950 text-[10px] font-black rounded-lg px-2.5 py-0.5 shadow-sm">{{ $pending }}</span>
                        @endif
                    </a>
                </div>
            </div>

        </nav>

        <!-- Current User Account Profile Footer -->
        <div class="p-4 border-t border-slate-900 bg-slate-950/60">
            @if(Auth::check())
            <div class="flex items-center gap-3 mb-4 px-2">
                @php
                    $adminName = Auth::user()->name;
                    $adminInitials = collect(explode(' ', $adminName))->map(fn($n) => strtoupper(substr($n, 0, 1)))->take(2)->join('');
                @endphp
                <div class="w-9 h-9 bg-gradient-to-tr from-primary to-royal rounded-xl flex items-center justify-center font-bold text-xs text-white">
                    {{ $adminInitials }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-xs font-bold text-slate-200 truncate leading-snug">{{ $adminName }}</div>
                    <div class="text-[10px] text-slate-500 font-semibold truncate leading-none mt-0.5">{{ Auth::user()->email }}</div>
                </div>
            </div>
            @endif
            
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-extrabold bg-rose-950/20 text-rose-400 hover:bg-rose-950/40 hover:text-rose-300 border border-rose-900/30 transition duration-200">
                    <i class="fa-solid fa-right-from-bracket"></i> Keluar Sesi
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Workspace Container -->
    <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
        
        <!-- Header Nav topbar -->
        <header class="bg-white border-b border-slate-200/80 px-6 py-4 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-4">
                <!-- Hamburger toggle button for Mobile -->
                <button onclick="toggleSidebar()" class="lg:hidden w-10 h-10 flex items-center justify-center rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 transition">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                
                <div>
                    <h1 class="text-lg font-black text-slate-900 tracking-tight leading-snug">@yield('page-title', 'Dashboard')</h1>
                    <p class="text-xs text-slate-400 font-semibold">@yield('page-subtitle', '')</p>
                </div>
            </div>
            
            <div class="hidden sm:flex items-center gap-3 text-xs font-bold text-slate-500 bg-slate-50 border border-slate-200/60 px-4 py-2 rounded-xl">
                <i class="fa-regular fa-calendar-days text-primary"></i>
                <span>{{ now()->translatedFormat('l, d F Y') }}</span>
            </div>
        </header>

        <!-- Scrollable content area -->
        <main class="flex-1 overflow-y-auto p-6 space-y-6 custom-scrollbar">
            <!-- Flash notification messages -->
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3.5 rounded-2xl flex items-center gap-2.5 shadow-sm text-xs font-bold">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-3.5 rounded-2xl flex items-center gap-2.5 shadow-sm text-xs font-bold">
                    <i class="fa-solid fa-circle-xmark text-rose-500 text-sm"></i>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
        
    </div>

    <!-- Responsive Sidebar script -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                setTimeout(() => {
                    backdrop.classList.add('opacity-100');
                }, 20);
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.remove('opacity-100');
                setTimeout(() => {
                    backdrop.classList.add('hidden');
                }, 300);
            }
        }
    </script>
    
    @stack('scripts')
</body>
</html>
