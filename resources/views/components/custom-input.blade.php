@props([
    'type' => 'text',
    'name',
    'id' => null,
    'label' => '',
    'icon' => null,
    'required' => false,
    'value' => '',
    'autofocus' => false,
    'autocomplete' => null,
])

@php
    $id = $id ?? $name;
@endphp

<div class="relative w-full" x-data="{ 
    showPassword: false,
    filled: {{ old($name, $value) ? 'true' : 'false' }},
    focus: false
}">
    <!-- Icon on the Left (for standard input types or standard icons) -->
    @if($icon && $type !== 'password')
        <span class="absolute inset-y-0 left-4 flex items-center text-gray-400 transition-colors duration-200"
              :class="focus ? 'text-primary' : 'text-gray-400'">
            <i class="{{ $icon }} text-lg"></i>
        </span>
    @elseif($type === 'password')
        <!-- Password visibility toggle on the left -->
        <button type="button" @click="showPassword = !showPassword" 
                class="absolute inset-y-0 left-4 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none transition-colors duration-200 cursor-pointer">
            <template x-if="!showPassword">
                <i class="fa-regular fa-eye-slash text-lg"></i>
            </template>
            <template x-if="showPassword">
                <i class="fa-regular fa-eye text-lg"></i>
            </template>
        </button>
    @endif

    <!-- Input field -->
    <input :type="showPassword ? 'text' : '{{ $type }}'" 
           name="{{ $name }}" 
           id="{{ $id }}" 
           value="{{ old($name, $value) }}"
           @focus="focus = true"
           @blur="focus = false; filled = ($el.value.length > 0)"
           @input="filled = ($el.value.length > 0)"
           {{ $required ? 'required' : '' }}
           {{ $autofocus ? 'autofocus' : '' }}
           @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif
           class="w-full text-right bg-white text-gray-800 px-4 py-4 rounded-xl border outline-hidden transition-all duration-200 focus:ring-1 placeholder-transparent
                  {{ $errors->has($name) ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-200 focus:border-primary focus:ring-primary' }}
                  {{ $icon || $type === 'password' ? 'pl-12' : '' }} pr-4"
           placeholder=" ">

    <!-- Floating Label -->
    <label for="{{ $id }}" 
           class="absolute right-4 text-gray-400 transition-all duration-300 pointer-events-none origin-top-right bg-white px-1.5
                  {{ old($name, $value) ? 'top-0 -translate-y-1/2 scale-75 font-bold' : '' }}"
           :class="focus || filled ? 'top-0 -translate-y-1/2 scale-75 font-bold ' + (focus ? '{{ $errors->has($name) ? 'text-red-500' : 'text-primary' }}' : '{{ $errors->has($name) ? 'text-red-500' : 'text-gray-500' }}') : 'top-1/2 -translate-y-1/2 scale-100 {{ $errors->has($name) ? 'text-red-400' : 'text-gray-400' }}'"
           style="transform-origin: top right;">
        {{ $label }}
    </label>
</div>
