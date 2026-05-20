<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — SportBook</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <style>
        .gradient-bg { background: linear-gradient(135deg, #064e3b 0%, #065f46 50%, #047857 100%); }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-2xl mb-4">
                <i class="fa-solid fa-futbol text-white text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-white">Sport<span class="text-amber-400">Book</span></h1>
            <p class="text-green-200 mt-1 text-sm">Admin Panel</p>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="bg-green-700 px-6 py-4">
                <h2 class="text-white font-semibold flex items-center gap-2">
                    <i class="fa-solid fa-lock"></i> Masuk ke Dashboard Admin
                </h2>
            </div>

            <div class="p-8">
                @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2 mb-5">
                    <i class="fa-solid fa-circle-check text-green-500"></i> {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2 mb-5">
                    <i class="fa-solid fa-circle-xmark text-red-500"></i> {{ session('error') }}
                </div>
                @endif

                <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fa-regular fa-envelope mr-1 text-green-600"></i> Email
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-transparent transition @error('email') border-red-400 bg-red-50 @enderror"
                               placeholder="admin@gmail.com">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                                <i class="fa-solid fa-triangle-exclamation"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fa-solid fa-key mr-1 text-green-600"></i> Password
                        </label>
                        <div class="relative">
                            <input type="password" name="password" id="password" required
                                   class="w-full border border-gray-300 rounded-xl px-4 py-3 pr-12 focus:ring-2 focus:ring-green-500 focus:border-transparent transition @error('password') border-red-400 bg-red-50 @enderror"
                                   placeholder="••••••••">
                            <button type="button" onclick="togglePassword()"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                                <i class="fa-regular fa-eye" id="eye-icon"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                                <i class="fa-solid fa-triangle-exclamation"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded text-green-600">
                            Ingat saya
                        </label>
                    </div>

                    <button type="submit"
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3.5 rounded-xl transition flex items-center justify-center gap-2 shadow-lg shadow-green-200">
                        <i class="fa-solid fa-right-to-bracket"></i> Masuk
                    </button>
                </form>
            </div>
        </div>

        <div class="text-center mt-6">
            <a href="{{ route('landing') }}" class="text-green-200 hover:text-white text-sm transition flex items-center justify-center gap-1">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Website
            </a>
        </div>
    </div>

    <script>
        function togglePassword() {
            const pwd  = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.className = 'fa-regular fa-eye-slash';
            } else {
                pwd.type = 'password';
                icon.className = 'fa-regular fa-eye';
            }
        }
    </script>
</body>
</html>
