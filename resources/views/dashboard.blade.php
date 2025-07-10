<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .subject-card { transition: all 0.3s ease; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .subject-card:hover { transform: translateY(-4px); box-shadow: 0 12px 24px -4px rgba(0, 0, 0, 0.15); }
        .search-bar { transition: all 0.3s ease; }
        .search-bar:focus { box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1); }
        .delete-btn { transition: all 0.2s ease; }
        .delete-btn:hover { transform: scale(1.1); }
        .add-btn { transition: all 0.3s ease; }
        .add-btn:hover { transform: scale(1.05); }
        .modal { backdrop-filter: blur(8px); }
        .fade-in { animation: fadeIn 0.3s ease-in-out; }
        .logout-btn { transition: all 0.2s ease; }
        .logout-btn:hover { background-color: #dc2626; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header with Logout Button -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Quiz Dashboard</h1>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn bg-red-500 text-white px-4 py-2 rounded-lg font-medium hover:bg-red-600 flex items-center space-x-2">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>

        <!-- Search and Add Section -->
        <div class="bg-white rounded-xl p-6 mb-8 shadow-lg">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <!-- Search Bar -->
                <div class="relative flex-1 max-w-md">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <input type="text" id="search-input" placeholder="Cari mata kuliah..." 
                           class="search-bar w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                </div>
                
                <!-- Add New Quiz Button -->
                <a href="{{ route('soal.create') }}" class="add-btn bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-lg font-medium hover:from-indigo-700 hover:to-purple-700 flex items-center space-x-2 shadow-lg">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Buat Soal Baru</span>
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl p-6 shadow-lg">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 006 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Mata Kuliah</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $subjects->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl p-6 shadow-lg">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg class="w-6 h-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Soal</p>
                        <p class="text-2xl font-bold text-gray-900">{{ App\Models\Soal::count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl p-6 shadow-lg">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-full">
                        <svg class="w-6 h-6 text-purple-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Quiz Selesai</p>
                        <p class="text-2xl font-bold text-gray-900">{{ App\Models\Kuis::count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subject Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="subjects-grid">
            @forelse ($subjects as $subject)
                <div class="subject-card bg-white rounded-xl p-6 relative group">
                    <!-- Delete Button -->
                    <form action="{{ route('mata_kuliah.delete', $subject->id) }}" method="POST" onsubmit="return confirmDelete('{{ $subject->nama_mata_kuliah }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-btn absolute top-3 right-3 w-8 h-8 bg-red-100 text-red-600 rounded-full hover:bg-red-200 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </form>
                    
                    <!-- Subject Content -->
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r {{ $subject->color ?? 'from-blue-500 to-blue-600' }} rounded-full flex items-center justify-center text-3xl text-white mx-auto mb-4">
                            @if ($subject->ikon)
                                <img src="{{ asset('storage/' . $subject->ikon) }}" alt="{{ $subject->nama_mata_kuliah }}" class="w-12 h-12 rounded-full">
                            @else
                                <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 006 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                </svg>
                            @endif
                        </div>
                        <h3 class="font-bold text-gray-800 text-lg mb-2">{{ $subject->nama_mata_kuliah }}</h3>
                        <div class="space-y-1 text-sm text-gray-600">
                            <p>{{ $subject->soal->count() }} soal tersedia</p>
                            <p>Terakhir diakses: {{ $subject->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="mt-4 flex space-x-2">
                        <a href="{{ route('kuis.mulai', $subject->id) }}" 
                            class="flex-1 bg-gradient-to-r {{ $subject->color ?? 'from-blue-500 to-blue-600' }} text-white py-2 px-4 rounded-lg font-medium hover:opacity-90 transition-opacity text-center block">
                             Mulai Quiz
                        </a>
                        <a href="{{ route('soal.edit', $subject->id) }}" 
                           class="px-3 py-2 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors flex items-center justify-center">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak ada mata kuliah ditemukan</h3>
                    <p class="text-gray-500">Coba buat mata kuliah baru atau periksa data Anda.</p>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.querySelector('#search-input');
            const cards = document.querySelectorAll('.subject-card');

            searchInput.addEventListener('input', function () {
                const query = searchInput.value.toLowerCase();

                cards.forEach(card => {
                    const title = card.querySelector('h3').textContent.toLowerCase();
                    card.style.display = title.includes(query) ? 'block' : 'none';
                });

                const visibleCards = Array.from(cards).filter(card => card.style.display !== 'none');
                document.querySelector('#subjects-grid').classList.toggle('hidden', visibleCards.length === 0);
                document.querySelector('.col-span-full').classList.toggle('hidden', visibleCards.length > 0);
            });
        });

        function confirmDelete(subjectName) {
            return confirm(`Hapus mata kuliah "${subjectName}"? Semua soal akan dihapus permanen.`);
        }
    </script>

    <footer class="mt-12 py-6 text-center text-gray-500 text-sm">
        © 2025 Froni Wahyudi. All rights reserved.
    </footer>
</body>
</html>