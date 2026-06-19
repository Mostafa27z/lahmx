@props([
    'type' => 'submit',
    'variant' => 'primary', // 'primary' or 'secondary'
])

<button type="{{ $type }}" 
        {{ $attributes->merge(['class' => 'w-full py-4 px-6 rounded-xl font-bold transition-all duration-200 shadow-xs cursor-pointer flex items-center justify-center gap-2 select-none active:scale-[0.98]
            ' . ($variant === 'primary' 
                ? 'bg-primary hover:bg-primary-dark text-white hover:shadow-md' 
                : 'bg-white hover:bg-gray-50 text-gray-700 border border-gray-200')
        ]) }}>
    {{ $slot }}
</button>
