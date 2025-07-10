<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Edukasi Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 font-['Inter'] relative overflow-hidden">

    <!-- Floating background shapes -->
    <div class="floating-shape w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full absolute top-[10%] left-[10%] opacity-10 animate-[float_6s_ease-in-out_infinite]"></div>
    <div class="floating-shape w-32 h-32 bg-gradient-to-r from-pink-500 to-orange-500 rounded-full absolute top-[20%] right-[10%] opacity-10 animate-[float_6s_ease-in-out_infinite_2s]"></div>
    <div class="floating-shape w-16 h-16 bg-gradient-to-r from-green-500 to-blue-500 rounded-full absolute bottom-[20%] left-[15%] opacity-10 animate-[float_6s_ease-in-out_infinite_4s]"></div>
    <div class="floating-shape w-24 h-24 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full absolute bottom-[10%] right-[20%] opacity-10 animate-[float_6s_ease-in-out_infinite_1s]"></div>

    <!-- Login Form -->
    <div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-md w-full space-y-8 animate-[fadeIn_0.8s_ease-in]">
            <div class="text-center animate-[slideUp_0.6s_ease-out]">
                <div class="flex justify-center mb-6">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <span class="text-white font-bold text-2xl">E</span>
                    </div>
                </div>
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Selamat Datang</h2>
                <p class="text-gray-600">Masuk ke Dashboard Edukasi Anda</p>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="backdrop-blur-lg bg-white/90 border border-white/20 rounded-2xl shadow-xl p-8 animate-[slideUp_0.6s_ease-out]">
                @csrf

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-300 text-red-600 px-4 py-2 rounded-xl text-sm mb-4">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="space-y-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19c-4.478 0-8.268-2.943-9.542-7a9 9 0 0117.084 0c-1.274 4.057-5.064 7-9.542 7z" />
                                </svg>
                            </div>
                            <input id="email" name="email" type="email" required value="{{ old('email') }}"
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                placeholder="Masukkan email">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m6 4H6a2 2 0 01-2-2v-6a2 2 0 012-2h12a2 2 0 012 2v6a2 2 0 01-2 2zM16 7a4 4 0 00-8 0v4h8V7z" />
                                </svg>
                            </div>
                            <input id="password" name="password" type="password" required
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                placeholder="Masukkan password">
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center text-sm text-gray-700">
                            <input type="checkbox" name="remember" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                            <span class="ml-2">Ingat saya</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a class="text-sm text-blue-600 hover:text-blue-500" href="{{ route('password.request') }}">
                                Lupa password?
                            </a>
                        @endif
                    </div>

                    <div>
                        <button type="submit"
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-white bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                            Masuk ke Dashboard
                        </button>
                    </div>
                </div>

                <div class="mt-6 p-4 bg-blue-50 rounded-xl border border-blue-200">
                    <p class="text-sm text-blue-700">
                        <strong>Demo Login:</strong><br>
                        Email: <code>demo@edukasi.com</code><br>
                        Password: <code>demo123</code>
                    </p>
                </div>
            </form>

            <div class="text-center animate-[slideUp_0.6s_ease-out]">
                <p class="text-sm text-gray-600">
                    Belum punya akun?
                    <a href="#" class="font-medium text-blue-600 hover:text-blue-500 transition-colors">Daftar sekarang</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
