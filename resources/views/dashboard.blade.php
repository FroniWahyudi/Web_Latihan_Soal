{{-- resources/views/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Dashboard</title>
    <link href="{{ asset('css/tailwind.min.css') }}" rel="stylesheet">
   

    <style>
        .subject-card { transition: all 0.3s ease; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .subject-card:hover { transform: translateY(-4px); box-shadow: 0 12px 24px -4px rgba(0, 0, 0, 0.15); }
        .search-bar:focus { box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1); }
        .delete-btn:hover { transform: scale(1.1); }
        .add-btn:hover { transform: scale(1.05); }
        .modal { backdrop-filter: blur(8px); }
        .fade-in { animation: fadeIn 0.3s ease-in-out; }
        .logout-btn:hover { background-color: #dc2626; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Quiz Dashboard</h1>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn bg-red-500 text-white px-4 py-2 rounded-lg font-medium hover:bg-red-600 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h3a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>

        <!-- Search & Add -->
        <div class="bg-white rounded-xl p-6 mb-8 shadow-lg">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <div class="relative flex-1 max-w-md">
                    <input type="text" placeholder="Cari mata kuliah..."
                        class="search-bar w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
                <a href="{{ route('soal.buat') }}" class="add-btn bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-lg font-medium hover:from-indigo-700 hover:to-purple-700 flex items-center space-x-2 shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Buat Soal Baru</span>
                </a>
            </div>
        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <x-dashboard.card iconColor="blue" title="Total Mata Kuliah" value="8" />
            <x-dashboard.card iconColor="green" title="Total Soal" value="127" />
            <x-dashboard.card iconColor="purple" title="Quiz Selesai" value="45" />
        </div>

        <!-- Mata Kuliah -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            {{-- Loop mata kuliah --}}
            @foreach($subjects as $subject)
                <x-dashboard.subject-card :subject="$subject" />
            @endforeach
        </div>
    </div>

    <footer class="mt-12 py-6 text-center text-gray-500 text-sm">
        &copy; 2025 Froni Wahyudi. All rights reserved.
    </footer>
</body>
</html>
