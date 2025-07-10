<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hasil Kuis {{ $kuis->mataKuliah->nama_mata_kuliah }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        .shake {
            animation: shake 0.5s ease-in-out;
        }
        .pulse-green {
            animation: pulseGreen 0.6s ease-in-out;
        }
        .pulse-red {
            animation: pulseRed 0.6s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        @keyframes pulseGreen {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); background-color: #10b981; }
            100% { transform: scale(1); }
        }
        @keyframes pulseRed {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); background-color: #ef4444; }
            100% { transform: scale(1); }
        }
        .navbar-sticky {
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.95);
        }
        .dropdown-menu {
            max-height: 200px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #a0aec0 #edf2f7;
        }
        .dropdown-menu::-webkit-scrollbar {
            width: 6px;
        }
        .dropdown-menu::-webkit-scrollbar-track {
            background: #edf2f7;
        }
        .dropdown-menu::-webkit-scrollbar-thumb {
            background: #a0aec0;
            border-radius: 3px;
        }
        .dropdown-menu::-webkit-scrollbar-thumb:hover {
            background: #718096;
        }
        .notification {
            position: fixed;
            top: 90px;
            right: 20px;
            z-index: 1000;
            animation: slideIn 0.3s ease-out;
        }
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="navbar-sticky fixed top-0 w-full z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="hidden md:flex items-center">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                            <span class="mr-2">🏠</span>
                            Dashboard
                        </a>
                        <div class="relative">
                            <button onclick="toggleDropdown('desktopDropdown')" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                                <span class="mr-2">📚</span>
                                Pilih Mata Kuliah
                            </button>
                            <div id="desktopDropdown" class="dropdown-menu hidden absolute mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                <div class="py-1">
                                    @foreach ($mataKuliah as $mk)
                                        <a href="{{ route('kuis.mulai', $mk->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600" onclick="selectCourse('{{ $mk->nama_mata_kuliah }}')">{{ $mk->nama_mata_kuliah }}</a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('logout') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:text-red-600 hover:bg-red-50 transition-colors">
                            <span class="mr-2">🚪</span>
                            Keluar
                        </a>
                    </div>
                </div>
                <div class="md:hidden flex items-center">
                    <button onclick="toggleMobileMenu()" class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div id="mobileMenu" class="md:hidden hidden border-t border-gray-200 bg-white">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 rounded-lg text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                    <span class="mr-2">🏠</span>
                    Dashboard
                </a>
                <div class="relative">
                    <button onclick="toggleDropdown('mobileDropdown')" class="flex items-center w-full px-3 py-2 rounded-lg text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                        <span class="mr-2">📚</span>
                        Pilih Mata Kuliah
                    </button>
                    <div id="mobileDropdown" class="dropdown-menu hidden mt-1 w-full bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                        <div class="py-1">
                            @foreach ($mataKuliah as $mk)
                                <a href="{{ route('kuis.mulai', $mk->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600" onclick="selectCourse('{{ $mk->nama_mata_kuliah }}')">{{ $mk->nama_mata_kuliah }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <a href="{{ route('logout') }}" class="flex items-center px-3 py-2 rounded-lg text-base font-medium text-gray-700 hover:text-red-600 hover:bg-red-50 transition-colors">
                    <span class="mr-2">🚪</span>
                    Keluar
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pt-24">
        <!-- Quiz Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Hasil Kuis {{ $kuis->mataKuliah->nama_mata_kuliah }}</h1>
            <p class="text-gray-600 text-lg">Berikut adalah hasil kuis Anda</p>
        </div>

        <!-- Results Card -->
        <div class="bg-white rounded-2xl shadow-sm p-8 mb-8 fade-in border border-gray-100" id="resultsCard">
            <div class="mb-6">
                <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Kuis Selesai!</h2>
                <p class="text-gray-600 text-lg">Berikut adalah hasil kuis Anda</p>
            </div>

            <!-- Results Summary -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-green-50 rounded-xl p-4 border border-green-200">
                    <div class="text-2xl font-bold text-green-600">{{ $kuis->skor }}</div>
                    <div class="text-sm text-green-700">Jawaban Benar</div>
                </div>
                <div class="bg-red-50 rounded-xl p-4 border border-red-200">
                    <div class="text-2xl font-bold text-red-600">{{ $kuis->jawaban->count() - $kuis->skor }}</div>
                    <div class="text-sm text-red-700">Jawaban Salah</div>
                </div>
                <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                    <div class="text-2xl font-bold text-blue-600">{{ $kuis->jawaban->count() > 0 ? round(($kuis->skor / $kuis->jawaban->count()) * 100) : 0 }}%</div>
                    <div class="text-sm text-blue-700">Persentase</div>
                </div>
            </div>

            <!-- Answer Details -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Ringkasan Jawaban</h3>
                <ul class="space-y-4">
                    @foreach ($kuis->jawaban as $jawaban)
                        <li class="bg-gray-50 p-4 rounded-lg {{ $jawaban->benar_salah ? 'border-green-200' : 'border-red-200' }} border">
                            <p class="font-medium text-gray-800">{{ $jawaban->soal->pertanyaan }}</p>
                            <p class="text-gray-600">Jawaban Anda: {{ $jawaban->jawaban_user }}</p>
                            <p class="text-gray-600">Status: 
                                <span class="{{ $jawaban->benar_salah ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $jawaban->benar_salah ? 'Benar' : 'Salah' }}
                                </span>
                            </p>
                            @if (!$jawaban->benar_salah)
                                <p class="text-gray-600">Jawaban Benar: {{ $jawaban->soal->jawaban_benar }}</p>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('dashboard') }}" class="bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-105">
                    Kembali ke Dashboard
                </a>
                <a href="{{ route('kuis.mulai', $kuis->mata_kuliah_id) }}" class="bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-105">
                    🔄 Mulai Ulang Kuis
                </a>
            </div>
        </div>

        <!-- Complete Card (Shown if all answers are correct) -->
        @if ($kuis->skor == $kuis->jawaban->count())
            <div class="bg-white rounded-2xl shadow-sm p-8 mb-8 fade-in border border-gray-100" id="completeCard">
                <div class="mb-6">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Sempurna! 🎉</h2>
                    <p class="text-gray-600 text-lg">Anda telah menyelesaikan semua soal dengan baik</p>
                </div>
                <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-xl p-6 mb-6 border border-green-200">
                    <p class="text-2xl font-bold text-green-600">Skor Final: {{ $kuis->skor }}/{{ $kuis->jawaban->count() }}</p>
                </div>
                <a href="{{ route('kuis.mulai', $kuis->mata_kuliah_id) }}" class="bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white font-semibold py-3 px-8 rounded-xl transition-all duration-200 transform hover:scale-105">
                    🔄 Mulai Kuis Baru
                </a>
            </div>
        @endif
    </main>

    <!-- Notification (Hidden initially) -->
    <div id="notification" class="notification hidden">
        <div class="bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            <span class="font-medium">Terjadi kesalahan!</span>
        </div>
    </div>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }

        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            dropdown.classList.toggle('hidden');
            const otherDropdownId = dropdownId === 'desktopDropdown' ? 'mobileDropdown' : 'desktopDropdown';
            const otherDropdown = document.getElementById(otherDropdownId);
            if (!otherDropdown.classList.contains('hidden')) {
                otherDropdown.classList.add('hidden');
            }
        }

        function selectCourse(course) {
            console.log(`Selected course: ${course}`);
            document.getElementById('desktopDropdown').classList.add('hidden');
            document.getElementById('mobileDropdown').classList.add('hidden');
        }

        function showNotification(message = 'Terjadi kesalahan!') {
            const notification = document.getElementById('notification');
            if (notification) {
                notification.querySelector('span').textContent = message;
                notification.classList.remove('hidden');
                setTimeout(() => {
                    notification.classList.add('hidden');
                }, 2000);
            }
        }

        document.addEventListener('click', function(event) {
            const desktopDropdown = document.getElementById('desktopDropdown');
            const mobileDropdown = document.getElementById('mobileDropdown');
            const desktopButton = document.querySelector('button[onclick*="desktopDropdown"]');
            const mobileButton = document.querySelector('button[onclick*="mobileDropdown"]');

            if (!desktopDropdown.contains(event.target) && !desktopButton.contains(event.target)) {
                desktopDropdown.classList.add('hidden');
            }
            if (!mobileDropdown.contains(event.target) && !mobileButton.contains(event.target)) {
                mobileDropdown.classList.add('hidden');
            }
        });
    </script>
</body>
</html>