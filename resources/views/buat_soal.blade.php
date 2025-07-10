<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tambah Soal Ujian</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .format-example {
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            border-left: 4px solid #3b82f6;
        }
        .textarea-container {
            transition: all 0.3s ease;
        }
        .textarea-container:focus-within {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        .preview-card {
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .correct-answer {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }
        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .success-animation {
            animation: successPulse 0.6s ease-in-out;
        }
        @keyframes successPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        .preview-container {
            max-height: 600px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #3b82f6 #e2e8f0;
        }
        .preview-container::-webkit-scrollbar {
            width: 8px;
        }
        .preview-container::-webkit-scrollbar-track {
            background: #e2e8f0;
            border-radius: 4px;
        }
        .preview-container::-webkit-scrollbar-thumb {
            background: #3b82f6;
            border-radius: 4px;
        }
        .preview-container::-webkit-scrollbar-thumb:hover {
            background: #2563eb;
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
    <div class="container mx-auto px-4 py-8 pt-24">
        @if (session('success'))
            <div class="notification">
                <div class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center success-animation">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="notification">
                <div class="bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <form action="{{ route('soal.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column - Input Area -->
                <div class="space-y-6">
                    <!-- Subject Selection -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 fade-in">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">📚 Nama Mata Kuliah</h3>
                        <select 
                            id="mata_kuliah_id"
                            name="mata_kuliah_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                            onchange="toggleCustomSubjectInput()"
                            required
                        >
                            <option value="" {{ old('mata_kuliah_id') ? '' : 'selected' }} disabled>Pilih mata kuliah...</option>
                            @forelse ($mataKuliah as $subject)
                                <option value="{{ $subject->id }}" {{ old('mata_kuliah_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->nama_mata_kuliah }}
                                </option>
                            @empty
                                <option value="" disabled>Tidak ada mata kuliah tersedia</option>
                            @endforelse
                            <option value="custom">Tulis Sendiri</option>
                        </select>
                        <div id="custom-subject-container" class="mt-4 hidden">
                            <input 
                                type="text" 
                                id="custom_mata_kuliah" 
                                name="custom_mata_kuliah" 
                                placeholder="Masukkan nama mata kuliah"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                value="{{ old('custom_mata_kuliah') }}"
                            >
                            @error('custom_mata_kuliah')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        @error('mata_kuliah_id')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Format Guide -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 fade-in">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">📋 Format Penulisan Soal</h3>
                        <div class="format-example p-4 rounded-lg mb-4">
                            <p class="text-sm font-medium text-gray-700 mb-2">Gunakan format berikut:</p>
                            <div class="bg-white p-3 rounded border font-mono text-sm">
                                <div class="text-gray-600">Q: [Pertanyaan Anda]</div>
                                <div class="text-gray-600">A: [Pilihan A]</div>
                                <div class="text-gray-600">B: [Pilihan B]</div>
                                <div class="text-gray-600">C: [Pilihan C] (correct)</div>
                                <div class="text-gray-600">D: [Pilihan D]</div>
                            </div>
                        </div>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                            <div class="flex items-start space-x-2">
                                <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                <div class="text-sm text-yellow-800">
                                    <p class="font-medium">Penting:</p>
                                    <ul class="mt-1 space-y-1">
                                        <li>• Tambahkan <code class="bg-yellow-100 px-1 rounded">(correct)</code> setelah jawaban yang benar</li>
                                        <li>• Pisahkan setiap soal dengan baris kosong</li>
                                        <li>• Gunakan Q: untuk pertanyaan, A:B:C:D: untuk pilihan</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Input Area -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 fade-in">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">✏️ Masukkan Soal</h3>
                            <button type="button" onclick="clearInput()" class="text-sm text-gray-500 hover:text-gray-700 underline">
                                Bersihkan
                            </button>
                        </div>
                        <div class="textarea-container rounded-lg border-2 border-gray-300 focus-within:border-blue-500">
                            <textarea 
                                id="questions-input" 
                                name="questions"
                                class="w-full h-80 p-4 resize-none outline-none font-mono text-sm"
                                oninput="parseQuestions()"
                            >{{ old('questions', "Q: Dalam Matematika Diskrit, apa itu himpunan?
A: Kumpulan bilangan bulat
B: Kumpulan objek tertentu yang terdefinisi dengan jelas (correct)
C: Kumpulan fungsi matematika
D: Kumpulan variabel acak

Q: Dalam Pemrograman Web, tag HTML apa yang digunakan untuk membuat tautan?
A: <link>
B: <a> (correct)
C: <href>
D: <url>

Q: Dalam Basis Data, apa fungsi utama perintah SQL SELECT?
A: Menghapus data
B: Menyisipkan data
C: Mengambil data (correct)
D: Memperbarui data") }}</textarea>
                            <div class="flex justify-between items-center mt-4">
                                <div class="text-sm text-gray-600">
                                    <span id="question-count">0</span> soal terdeteksi
                                </div>
                                <button type="submit" id="save-btn" 
                                        class="bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 disabled:bg-gray-300 disabled:cursor-not-allowed text-white px-6 py-2 rounded-xl font-medium transition-colors">
                                    💾 Simpan Soal
                                </button>
                            </div>
                        </div>
                        @error('questions')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column - Preview -->
                <div class="space-y-6">
                    <!-- Preview Header -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 fade-in">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">👀 Pratinjau Soal</h3>
                        <p class="text-gray-600 text-sm">Pratinjau soal yang akan disimpan</p>
                    </div>

                    <!-- Questions Preview -->
                    <div id="questions-preview" class="space-y-4 preview-container">
                        <div class="bg-gray-50 rounded-2xl p-8 text-center border border-gray-100">
                            <div class="text-4xl mb-3">📝</div>
                            <p class="text-gray-600">Masukkan soal di sebelah kiri untuk melihat pratinjau</p>
                        </div>
                    </div>

                    <!-- Statistics -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 fade-in" id="stats-card" style="display: none;">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">📊 Statistik</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-3 bg-blue-50 rounded-xl border border-blue-200">
                                <div class="text-2xl font-bold text-blue-600" id="total-questions-stat">0</div>
                                <div class="text-sm text-blue-700">Total Soal</div>
                            </div>
                            <div class="text-center p-3 bg-green-50 rounded-xl border border-green-200">
                                <div class="text-2xl font-bold text-green-600" id="valid-questions-stat">0</div>
                                <div class="text-sm text-green-700">Soal Valid</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        let parsedQuestions = [];

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

        function toggleCustomSubjectInput() {
            const select = document.getElementById('mata_kuliah_id');
            const customContainer = document.getElementById('custom-subject-container');
            const customInput = document.getElementById('custom_mata_kuliah');
            
            if (select.value === 'custom') {
                customContainer.classList.remove('hidden');
                customInput.required = true;
            } else {
                customContainer.classList.add('hidden');
                customInput.required = false;
                customInput.value = '';
            }
            updateStats();
        }

        function parseQuestions() {
            const input = document.getElementById('questions-input').value;
            const lines = input.split('\n').map(line => line.trim()).filter(line => line);
            
            parsedQuestions = [];
            let currentQuestion = null;
            
            for (let line of lines) {
                if (line.startsWith('Q:')) {
                    if (currentQuestion && currentQuestion.question && currentQuestion.options.length === 4) {
                        parsedQuestions.push(currentQuestion);
                    }
                    currentQuestion = {
                        question: line.substring(2).trim(),
                        options: [],
                        correct: -1
                    };
                } else if (line.match(/^[A-D]:/)) {
                    if (currentQuestion) {
                        const optionText = line.substring(2).trim();
                        const isCorrect = optionText.includes('(correct)');
                        const cleanText = optionText.replace('(correct)', '').trim();
                        currentQuestion.options.push(cleanText);
                        if (isCorrect) {
                            currentQuestion.correct = currentQuestion.options.length - 1;
                        }
                    }
                }
            }
            
            if (currentQuestion && currentQuestion.question && currentQuestion.options.length === 4) {
                parsedQuestions.push(currentQuestion);
            }
            
            updatePreview();
            updateStats();
        }

        function updatePreview() {
            const preview = document.getElementById('questions-preview');
            
            if (parsedQuestions.length === 0) {
                preview.innerHTML = `
                    <div class="bg-gray-50 rounded-2xl p-8 text-center border border-gray-100">
                        <div class="text-4xl mb-3">📝</div>
                        <p class="text-gray-600">Masukkan soal di sebelah kiri untuk melihat pratinjau</p>
                    </div>
                `;
                return;
            }
            
            preview.innerHTML = parsedQuestions.map((q, index) => `
                <div class="preview-card bg-white rounded-2xl p-6 border border-gray-100 fade-in">
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full mb-4">
                            <span class="text-blue-600 font-bold text-lg">${index + 1}</span>
                        </div>
                        <h4 class="text-xl font-semibold text-gray-800 leading-relaxed">${q.question}</h4>
                    </div>
                    <div class="grid grid-cols-1 gap-4">
                        ${q.options.map((option, optIndex) => `
                            <div class="p-4 rounded-xl border ${optIndex === q.correct ? 'correct-answer border-green-500' : 'bg-gray-50 border-gray-200'} transition-all duration-200">
                                <div class="flex items-center">
                                    <span class="w-8 h-8 ${optIndex === q.correct ? 'bg-white text-green-600' : 'bg-blue-100 text-blue-600'} rounded-full flex items-center justify-center font-semibold mr-4">${String.fromCharCode(65 + optIndex)}</span>
                                    <span class="text-lg font-medium ${optIndex === q.correct ? 'text-white' : 'text-gray-700'}">${option}</span>
                                    ${optIndex === q.correct ? '<span class="ml-auto text-white">✓ Benar</span>' : ''}
                                </div>
                            </div>
                        `).join('')}
                    </div>
                    ${q.correct === -1 ? '<div class="mt-3 text-sm text-red-600">⚠️ Jawaban benar belum ditandai</div>' : ''}
                </div>
            `).join('');
        }

        function updateStats() {
            const questionCount = parsedQuestions.length;
            const validQuestions = parsedQuestions.filter(q => q.correct !== -1).length;
            const select = document.getElementById('mata_kuliah_id').value.trim();
            const customInput = document.getElementById('custom_mata_kuliah').value.trim();

            document.getElementById('question-count').textContent = questionCount;
            document.getElementById('total-questions-stat').textContent = questionCount;
            document.getElementById('valid-questions-stat').textContent = validQuestions;

            const statsCard = document.getElementById('stats-card');
            if (questionCount > 0) {
                statsCard.style.display = 'block';
            } else {
                statsCard.style.display = 'none';
            }

            const saveBtn = document.getElementById('save-btn');
            saveBtn.disabled = !(validQuestions > 0 && (select !== '' && select !== 'custom' || (select === 'custom' && customInput !== '')));
        }

        function clearInput() {
            document.getElementById('questions-input').value = '';
            parseQuestions();
        }

        document.getElementById('mata_kuliah_id').addEventListener('input', () => {
            toggleCustomSubjectInput();
            updateStats();
        });
        document.getElementById('custom_mata_kuliah')?.addEventListener('input', updateStats);

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

        // Initialize preview and custom input state on page load
        parseQuestions();
        toggleCustomSubjectInput();
    </script>
</body>
</html>