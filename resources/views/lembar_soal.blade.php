<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kuis {{ $kuis->mataKuliah->nama_mata_kuliah }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .progress-bar {
            transition: width 0.3s ease-in-out;
        }
        .option-button {
            transition: all 0.2s ease-in-out;
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
        .shake {
            animation: shake 0.5s ease-in-out;
        }
        .pulse-green {
            animation: pulseGreen 0.6s ease-in-out;
        }
        .pulse-red {
            animation: pulseRed 0.6s ease-in-out;
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
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Kuis {{ $kuis->mataKuliah->nama_mata_kuliah }}</h1>
            <p class="text-gray-600">Jawab semua pertanyaan dengan benar untuk mendapatkan skor maksimal</p>
        </div>

        <!-- Progress Section -->
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-8 border border-gray-100">
            <div class="flex justify-between items-center mb-4">
                <span class="text-sm font-medium text-gray-600">Progress Kuis</span>
                <span class="text-sm font-semibold text-blue-600">Soal {{ $index + 1 }} dari {{ $totalSoal }}</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div id="progressBar" class="bg-gradient-to-r from-blue-500 to-purple-500 h-3 rounded-full progress-bar"></div>
            </div>
        </div>

        <!-- Question Card -->
        <div class="bg-white rounded-2xl shadow-sm p-8 mb-8 fade-in border border-gray-100">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full mb-4">
                    <span class="text-blue-600 font-bold text-lg">{{ $index + 1 }}</span>
                </div>
                <h2 class="text-2xl font-semibold text-gray-800 leading-relaxed">{{ $soal->pertanyaan }}</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="optionsContainer">
                @foreach (['A' => $soal->pilihan_a, 'B' => $soal->pilihan_b, 'C' => $soal->pilihan_c, 'D' => $soal->pilihan_d] as $key => $pilihan)
                    <label class="option-button bg-gray-50 hover:bg-gray-100 border-2 border-gray-200 rounded-xl p-6 text-left transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5" data-value="{{ $key }}">
                        <input type="radio" name="jawaban_radio" value="{{ $key }}" class="hidden" {{ isset($jawaban[$soal->id]) && $jawaban[$soal->id] == $key ? 'checked' : '' }} required>
                        <div class="flex items-center">
                            <span class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-semibold mr-4">{{ $key }}</span>
                            <span class="text-lg font-medium text-gray-700">{{ $pilihan }}</span>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="flex justify-between mt-8">
            @if ($index > 0)
                <a href="{{ route('kuis.soal', ['kuisId' => $kuis->id, 'index' => $index - 1]) }}" class="bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-xl">Sebelumnya</a>
            @else
                <button type="button" class="bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-xl" disabled>Sebelumnya</button>
            @endif
        </div>
    </main>

    <!-- Notification (Hidden initially) -->
    <div id="notification" class="notification hidden">
        <div class="bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            <span class="font-medium">Jawaban Salah!</span>
        </div>
    </div>

    <script>
        let isAnswering = false;

        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }

        document.querySelectorAll('input[name="jawaban_radio"]').forEach(input => {
            input.addEventListener('change', function() {
                if (isAnswering) return;
                isAnswering = true;

                const selectedValue = this.value;

                document.querySelectorAll('.option-button').forEach(opt => {
                    opt.classList.add('pointer-events-none');
                });

                fetch("{{ route('kuis.simpanJawaban', ['kuisId' => $kuis->id, 'index' => $index]) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        jawaban: selectedValue
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.error || `HTTP error! Status: ${response.status}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data); // Debugging
                    if (data.error) {
                        showNotification(data.error);
                        isAnswering = false;
                        document.querySelectorAll('.option-button').forEach(opt => {
                            opt.classList.remove('pointer-events-none');
                        });
                        return;
                    }

                    const options = document.querySelectorAll('.option-button');
                    const correctAnswer = data.correct_answer;

                    options.forEach(option => {
                        const value = option.getAttribute('data-value');
                        const letterSpan = option.querySelector('span:first-child');
                        if (value === selectedValue) {
                            if (value === correctAnswer) {
                                option.classList.add('bg-green-500', 'text-white', 'border-green-500', 'pulse-green');
                                letterSpan.className = 'w-8 h-8 bg-white text-green-600 rounded-full flex items-center justify-center font-semibold mr-4';
                            } else {
                                option.classList.add('bg-red-500', 'text-white', 'border-red-500', 'pulse-red', 'shake');
                                letterSpan.className = 'w-8 h-8 bg-white text-red-600 rounded-full flex items-center justify-center font-semibold mr-4';
                                showNotification('Jawaban Salah!');
                            }
                        } else if (value === correctAnswer) {
                            option.classList.add('bg-green-500', 'text-white', 'border-green-500', 'pulse-green');
                            letterSpan.className = 'w-8 h-8 bg-white text-green-600 rounded-full flex items-center justify-center font-semibold mr-4';
                        }
                    });

                    setTimeout(() => {
                        if (data.next_url) {
                            console.log('Redirecting to:', data.next_url); // Debugging
                            window.location.href = data.next_url; // Arahkan ke soal berikutnya atau hasil
                        } else {
                            showNotification('Terjadi kesalahan, silakan coba lagi.');
                            isAnswering = false;
                            document.querySelectorAll('.option-button').forEach(opt => {
                                opt.classList.remove('pointer-events-none');
                            });
                        }
                    }, data.is_correct ? 1500 : 2500);
                })
                .catch(error => {
                    console.error('Fetch error:', error.message);
                    showNotification('Gagal menghubungi server: ' + error.message);
                    isAnswering = false;
                    document.querySelectorAll('.option-button').forEach(opt => {
                        opt.classList.remove('pointer-events-none');
                    });
                });
            });
        });

        function showNotification(message) {
            const notification = document.getElementById('notification');
            if (notification) {
                notification.querySelector('span').textContent = message;
                notification.classList.remove('hidden');
                setTimeout(() => {
                    notification.classList.add('hidden');
                }, 2000);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const progressBar = document.getElementById('progressBar');
            if (progressBar) {
                progressBar.style.width = '{{ $totalSoal > 0 ? (($index + 1) / $totalSoal) * 100 : 0 }}%';
            }
        });
    </script>
</body>
</html>