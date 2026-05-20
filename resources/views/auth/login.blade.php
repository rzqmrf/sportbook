<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — E-ReservLap</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    
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
                            dark: '#047857',
                        },
                        royal: {
                            DEFAULT: '#2563eb',
                            dark: '#1d4ed8',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-955 bg-slate-950 font-sans text-slate-100 antialiased min-h-screen flex items-center justify-center px-4 relative overflow-hidden">
    
    <!-- Aurora background decoration glows -->
    <div class="absolute w-[500px] h-[500px] bg-primary/20 rounded-full blur-[120px] -top-40 -left-20 z-0"></div>
    <div class="absolute w-[500px] h-[500px] bg-royal/15 rounded-full blur-[120px] -bottom-40 -right-20 z-0"></div>

    <div class="w-full max-w-md relative z-10 my-8">
        
        <!-- Logo Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-tr from-primary to-royal rounded-2.5xl shadow-xl shadow-primary/10 mb-4 border border-white/10">
                <i class="fa-solid fa-gauge-high text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-black tracking-tight text-white leading-none">E-Reserv<span class="text-primary">Lap</span></h1>
            <p class="text-slate-400 font-extrabold text-[10px] uppercase tracking-widest mt-2 bg-white/5 border border-white/10 rounded-full py-1 px-4 inline-block">
                🔒 Admin Control Console
            </p>
        </div>

        <!-- Form Card -->
        <div class="bg-slate-900/65 backdrop-blur-2xl rounded-[2.5xl] border border-slate-800 p-8.5 sm:p-10 shadow-2xl shadow-black/60 relative overflow-hidden">
            
            <div class="mb-6.5 text-center sm:text-left">
                <h2 class="text-xl font-black text-slate-100 tracking-tight">Otentikasi Admin</h2>
                <p class="text-slate-400 text-xs font-semibold mt-1">Masukkan kredensial admin Anda untuk melanjutkan ke dashboard.</p>
            </div>

            <!-- Flash Session Alerts -->
            @if(session('success'))
            <div class="bg-emerald-950/40 border border-emerald-900 text-emerald-300 px-4.5 py-3 rounded-2xl text-xs font-bold flex items-start gap-2.5 mb-6">
                <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5 text-sm"></i>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-rose-950/40 border border-rose-900 text-rose-300 px-4.5 py-3 rounded-2xl text-xs font-bold flex items-start gap-2.5 mb-6">
                <i class="fa-solid fa-circle-xmark text-rose-500 mt-0.5 text-sm"></i>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Email Input -->
                <div class="space-y-2">
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Alamat Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4.5 flex items-center text-slate-500">
                            <i class="fa-regular fa-envelope"></i>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full border border-slate-800 bg-slate-950/40 rounded-2xl pl-11 pr-4.5 py-3.5 focus:bg-slate-950 focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200 text-slate-200 placeholder-slate-600 font-semibold text-sm @error('email') border-rose-900 bg-rose-950/10 @enderror"
                               placeholder="admin@reservlap.com">
                    </div>
                    @error('email')
                        <p class="text-rose-500 text-[11px] font-semibold mt-1">
                            <i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password Input -->
                <div class="space-y-2">
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kata Sandi</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4.5 flex items-center text-slate-500">
                            <i class="fa-solid fa-key"></i>
                        </span>
                        <input type="password" name="password" id="password" required
                               class="w-full border border-slate-800 bg-slate-950/40 rounded-2xl pl-11 pr-12 py-3.5 focus:bg-slate-950 focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200 text-slate-200 placeholder-slate-600 font-semibold text-sm @error('password') border-rose-900 bg-rose-950/10 @enderror"
                               placeholder="••••••••">
                        <button type="button" onclick="togglePassword()"
                                class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 transition px-1 py-0.5">
                            <i class="fa-regular fa-eye text-sm" id="eye-icon"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-rose-500 text-[11px] font-semibold mt-1">
                            <i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2.5 text-xs font-semibold text-slate-400 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="w-4.5 h-4.5 rounded border-slate-800 bg-slate-950/50 text-primary focus:ring-primary focus:ring-offset-slate-900">
                        Ingat saya
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                        class="w-full bg-primary hover:bg-primary-dark text-white font-extrabold text-sm py-4 rounded-2xl transition duration-200 flex items-center justify-center gap-2 shadow-lg shadow-primary/10 mt-2">
                    Masuk Ke Console <i class="fa-solid fa-right-to-bracket text-xs"></i>
                </button>
            </form>
        </div>

        <!-- Back Link -->
        <div class="text-center mt-8 relative z-10">
            <a href="/" class="text-slate-500 hover:text-slate-300 text-xs font-bold transition flex items-center justify-center gap-1.5 uppercase tracking-wider">
                <i class="fa-solid fa-arrow-left text-[10px]"></i> Kembali ke Landing Page
            </a>
        </div>
    </div>

    <!-- Password visibility toggle script -->
    <script>
        function togglePassword() {
            const pwd  = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.className = 'fa-regular fa-eye-slash text-sm';
            } else {
                pwd.type = 'password';
                icon.className = 'fa-regular fa-eye text-sm';
            }
        }
    </script>
</body>
</html>
