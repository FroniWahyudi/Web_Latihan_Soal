<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Soal Ujian</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
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
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <div class="container mx-auto px-4 py-4">
        <a href="{{ route('dashboard') }}" 
           class="mb-6 bg-gray-200 hover:bg-gray-300 text-gray-800 px-5 py-2 rounded-lg font-medium flex items-center space-x-2 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            <span>Kembali ke Daftar Soal</span>
        </a>
    </div>

    <div class="container mx-auto px-4 py-8">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 fade-in" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 fade-in" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('soal.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column - Input Area -->
                <div class="space-y-6">
                    <!-- Subject Selection -->
                    <div class="bg-white rounded-xl p-6 shadow-lg">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">📚 Nama Mata Kuliah</h3>
                        <select 
                            id="mata_kuliah_id"
                            name="mata_kuliah_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none"
                            onchange="toggleCustomSubjectInput()"
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
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none"
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
                    <div class="bg-white rounded-xl p-6 shadow-lg">
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
                    <div class="bg-white rounded-xl p-6 shadow-lg">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">✏️ Masukkan Soal</h3>
                            <button type="button" onclick="clearInput()" class="text-sm text-gray-500 hover:text-gray-700 underline">
                                Bersihkan
                            </button>
                        </div>
                        
                        <div class="textarea-container rounded-lg border-2 border-gray-300 focus-within:border-indigo-500">
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
                                        class="bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white px-6 py-2 rounded-lg font-medium transition-colors">
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
                    <div class="bg-white rounded-xl p-6 shadow-lg">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">👀 Preview Soal</h3>
                        <p class="text-gray-600 text-sm">Pratinjau soal yang akan disimpan</p>
                    </div>

                    <!-- Questions Preview -->
                    <div id="questions-preview" class="space-y-4 preview-container">
                        <div class="bg-gray-50 rounded-xl p-8 text-center">
                            <div class="text-4xl mb-3">📝</div>
                            <p class="text-gray-600">Masukkan soal di sebelah kiri untuk melihat preview</p>
                        </div>
                    </div>

                    <!-- Statistics -->
                    <div class="bg-white rounded-xl p-6 shadow-lg" id="stats-card" style="display: none;">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">📊 Statistik</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-3 bg-blue-50 rounded-lg">
                                <div class="text-2xl font-bold text-blue-600" id="total-questions-stat">0</div>
                                <div class="text-sm text-gray-600">Total Soal</div>
                            </div>
                            <div class="text-center p-3 bg-green-50 rounded-lg">
                                <div class="text-2xl font-bold text-green-600" id="valid-questions-stat">0</div>
                                <div class="text-sm text-gray-600">Soal Valid</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        let parsedQuestions = [];

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
                    <div class="bg-gray-50 rounded-xl p-8 text-center">
                        <div class="text-4xl mb-3">📝</div>
                        <p class="text-gray-600">Masukkan soal di sebelah kiri untuk melihat preview</p>
                    </div>
                `;
                return;
            }
            
            preview.innerHTML = parsedQuestions.map((q, index) => `
                <div class="preview-card bg-white rounded-xl p-6 fade-in">
                    <div class="mb-4">
                        <div class="text-sm font-medium text-indigo-600 mb-2">Soal ${index + 1}</div>
                        <h4 class="font-semibold text-gray-800">${q.question}</h4>
                    </div>
                    <div class="space-y-2">
                        ${q.options.map((option, optIndex) => `
                            <div class="p-3 rounded-lg border ${optIndex === q.correct ? 'correct-answer border-green-500' : 'border-gray-200 bg-gray-50'}">
                                <span class="font-medium">${String.fromCharCode(65 + optIndex)}.</span> ${option}
                                ${optIndex === q.correct ? '<span class="float-right">✓ Benar</span>' : ''}
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

        // Initialize preview and custom input state on page load
        parseQuestions();
        toggleCustomSubjectInput();
    </script>
</body>
</html>