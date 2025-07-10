@props(['subject'])

<div class="subject-card bg-white rounded-xl p-6 shadow hover:shadow-lg transition">
    <div class="flex items-center space-x-4">
        @if ($subject->ikon)
            <img src="{{ asset('storage/' . $subject->ikon) }}" alt="{{ $subject->nama_mata_kuliah }}" class="w-12 h-12">
        @else
            <svg class="w-12 h-12 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 006 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
            </svg>
        @endif
        <div>
            <h3 class="text-lg font-semibold text-gray-800">{{ $subject->nama_mata_kuliah }}</h3>
            <a href="{{ route('soal.index') }}" class="text-blue-600 hover:underline">Lihat Soal</a>
        </div>
    </div>
</div>