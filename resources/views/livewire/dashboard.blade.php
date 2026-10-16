<div>
    {{-- card for each level with tailwind --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @foreach ($levels as $level)
            <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center">
                <h2 class="text-xl font-semibold mb-4">{{ $level->name }}</h2>
                <p class="text-gray-600 mb-4">Price: ${{ number_format($level->price, 2) }}</p>
                <a href="{{ route('checkout', ['level' => $level->slug]) }}"
                    class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">
                    Select
                </a>
            </div>
        @endforeach
    </div>
</div>
