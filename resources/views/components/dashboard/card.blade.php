@props(['iconColor' => 'blue', 'title' => '', 'value' => 0])

<div class="bg-white rounded-xl shadow p-6 flex items-center space-x-4">
    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-{{ $iconColor }}-100 text-{{ $iconColor }}-600">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m4 0H5" />
        </svg>
    </div>
    <div>
        <div class="text-gray-500 text-sm">{{ $title }}</div>
        <div class="text-xl font-semibold text-gray-800">{{ $value }}</div>
    </div>
</div>
