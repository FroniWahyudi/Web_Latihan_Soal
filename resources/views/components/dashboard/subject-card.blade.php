@props(['subject'])

<div class="subject-card bg-white rounded-xl p-6 shadow hover:shadow-lg transition">
    <h3 class="text-lg font-semibold text-gray-800">{{ $subject->nama }}</h3>
    <p class="text-sm text-gray-600 mt-2">{{ $subject->deskripsi }}</p>
</div>
