@props(['title', 'subtitle'])

<div class="py-16 bg-bg-custom flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden border border-red-50">
        <!-- Accent bar -->
        <div class="h-2 bg-primary"></div>
        
        <div class="p-8">
            <div class="text-center mb-8">
                <span class="text-4xl">🥩</span>
                <h2 class="text-3xl font-extrabold text-primary mt-4">{{ $title }}</h2>
                @if(isset($subtitle))
                    <p class="text-gray-500 mt-2">{{ $subtitle }}</p>
                @endif
            </div>

            {{ $slot }}
        </div>
    </div>
</div>
