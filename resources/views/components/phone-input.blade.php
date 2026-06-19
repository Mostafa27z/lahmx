@props([
    'name' => 'phone',
    'id' => null,
    'label' => 'رقم الجوال',
    'required' => false,
    'value' => '',
])

@php
    $id = $id ?? $name;
@endphp

<div class="relative w-full" x-data="{
    open: false,
    focus: false,
    filled: false,
    countries: [
        { name: 'Saudi Arabia', nameAr: 'السعودية', code: '+966', flag: '🇸🇦', iso: 'sa' },
        { name: 'United Arab Emirates', nameAr: 'الإمارات', code: '+971', flag: '🇦🇪', iso: 'ae' },
        { name: 'Egypt', nameAr: 'مصر', code: '+20', flag: '🇪🇬', iso: 'eg' },
        { name: 'Turkey', nameAr: 'تركيا', code: '+90', flag: '🇹🇷', iso: 'tr' },
        { name: 'Turkmenistan', nameAr: 'تركمانستان', code: '+993', flag: '🇹🇲', iso: 'tm' },
        { name: 'Tuvalu', nameAr: 'توفالو', code: '+688', flag: '🇹🇻', iso: 'tv' },
        { name: 'Uganda', nameAr: 'أوغندا', code: '+256', flag: '🇺🇬', iso: 'ug' },
        { name: 'Ukraine', nameAr: 'أوكرانيا', code: '+380', flag: '🇺🇦', iso: 'ua' },
        { name: 'Jordan', nameAr: 'الأردن', code: '+962', flag: '🇯🇴', iso: 'jo' },
        { name: 'Kuwait', nameAr: 'الكويت', code: '+965', flag: '🇰🇼', iso: 'kw' },
        { name: 'Qatar', nameAr: 'قطر', code: '+974', flag: '🇶🇦', iso: 'qa' },
        { name: 'Bahrain', nameAr: 'البحرين', code: '+973', flag: '🇧🇭', iso: 'bh' },
        { name: 'Oman', nameAr: 'عمان', code: '+968', flag: '🇴🇲', iso: 'om' }
    ],
    selectedCountry: null,
    visibleNumber: '',
    fullNumber: '',
    
    init() {
        // Sort countries alphabetically by English name
        this.countries.sort((a, b) => a.name.localeCompare(b.name));
        
        // Default selected country: Saudi Arabia
        this.selectedCountry = this.countries.find(c => c.iso === 'sa');
        
        let initialVal = '{{ old($name, $value) }}';
        if (initialVal) {
            // Find country code that matches initial value
            // Sort countries by code length descending to match longest code first (e.g. +971 over +9)
            let sortedByCodeLen = [...this.countries].sort((a, b) => b.code.length - a.code.length);
            let found = false;
            for (let c of sortedByCodeLen) {
                if (initialVal.startsWith(c.code)) {
                    this.selectedCountry = c;
                    this.visibleNumber = initialVal.substring(c.code.length);
                    found = true;
                    break;
                }
            }
            if (!found) {
                this.visibleNumber = initialVal;
            }
            this.filled = this.visibleNumber.length > 0;
        }
        this.updateFullNumber();
    },
    
    selectCountry(country) {
        this.selectedCountry = country;
        this.open = false;
        this.updateFullNumber();
        this.$refs.phoneInput.focus();
    },
    
    onInput(e) {
        let val = e.target.value;
        this.visibleNumber = val;
        this.filled = val.length > 0;
        
        // Parse code if user types it (e.g. starting with + or 00 or just country code)
        let cleanVal = val.trim();
        if (cleanVal.startsWith('+') || cleanVal.startsWith('00')) {
            let sortedByCodeLen = [...this.countries].sort((a, b) => b.code.length - a.code.length);
            for (let c of sortedByCodeLen) {
                let codeAlt = c.code.replace('+', '00');
                if (cleanVal.startsWith(c.code)) {
                    this.selectedCountry = c;
                    this.visibleNumber = cleanVal.substring(c.code.length);
                    break;
                } else if (cleanVal.startsWith(codeAlt)) {
                    this.selectedCountry = c;
                    this.visibleNumber = cleanVal.substring(codeAlt.length);
                    break;
                }
            }
        }
        
        this.updateFullNumber();
    },
    
    updateFullNumber() {
        if (this.selectedCountry && this.visibleNumber) {
            this.fullNumber = this.selectedCountry.code + this.visibleNumber;
        } else {
            this.fullNumber = this.visibleNumber;
        }
    }
}">
    <!-- Hidden input to hold the combined full phone number -->
    <input type="hidden" name="{{ $name }}" :value="fullNumber">

    <div class="relative flex items-center w-full">
        <!-- Country Selector Trigger (Left Aligned in input container) -->
        <div class="absolute left-3 flex items-center z-10">
            <button type="button" @click="open = !open" 
                    class="flex items-center gap-1.5 px-1.5 py-1 rounded-lg hover:bg-gray-100 transition-colors duration-200 text-gray-700 font-bold text-sm cursor-pointer select-none focus:outline-none">
                <template x-if="selectedCountry">
                    <img :src="'https://flagcdn.com/w40/' + selectedCountry.iso + '.png'" 
                         class="w-5 h-3.5 object-cover rounded-xs border border-gray-100 shadow-2xs" 
                         :alt="selectedCountry.nameAr">
                </template>
                <span x-text="selectedCountry?.code" class="text-xs font-bold text-gray-600" dir="ltr"></span>
                <i class="fa-solid fa-caret-down text-[10px] text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
            </button>
            
            <!-- Vertical Divider -->
            <div class="h-6 w-px bg-gray-200 ml-1.5"></div>
        </div>

        <!-- Phone input field -->
        <input type="text" 
               x-ref="phoneInput"
               id="{{ $id }}_visible" 
               :value="visibleNumber"
               @focus="focus = true"
               @blur="focus = false; filled = (visibleNumber.length > 0)"
               @input="onInput"
               placeholder=" "
               dir="ltr"
               {{ $required ? 'required' : '' }}
               class="w-full text-left bg-white text-gray-800 px-4 py-4 rounded-xl border outline-hidden transition-all duration-200 focus:ring-1 placeholder-transparent pl-28 pr-4
                      {{ $errors->has($name) ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-200 focus:border-primary focus:ring-primary' }}">

        <!-- Floating Label -->
        <label for="{{ $id }}_visible" 
               class="absolute right-4 text-gray-400 transition-all duration-300 pointer-events-none origin-top-right bg-white px-1.5
                      {{ old($name, $value) ? 'top-0 -translate-y-1/2 scale-75 text-gray-500 font-bold' : '' }}"
               :class="focus || filled ? 'top-0 -translate-y-1/2 scale-75 font-bold ' + (focus ? '{{ $errors->has($name) ? 'text-red-500' : 'text-primary' }}' : '{{ $errors->has($name) ? 'text-red-500' : 'text-gray-500' }}') : 'top-1/2 -translate-y-1/2 scale-100 {{ $errors->has($name) ? 'text-red-400' : 'text-gray-400' }}'"
               style="transform-origin: top right;">
            {{ $label }}
        </label>
    </div>

    <!-- Country Dropdown Menu -->
    <div x-show="open" @click.away="open = false"
         class="absolute left-0 mt-2 w-72 bg-white rounded-xl shadow-xl border border-gray-100 z-50 text-right overflow-hidden"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 -translate-y-2">
         
        <div class="max-h-60 overflow-y-auto p-1.5 space-y-0.5 scrollbar-thin">
            <template x-for="c in countries" :key="c.iso">
                <button type="button" @click="selectCountry(c)"
                        class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-semibold hover:bg-gray-50 text-gray-700 transition duration-150 cursor-pointer select-none">
                    <span class="flex items-center gap-2.5">
                        <img :src="'https://flagcdn.com/w40/' + c.iso + '.png'" 
                             class="w-5 h-3.5 object-cover rounded-xs border border-gray-100 shadow-2xs" 
                             :alt="c.nameAr">
                        <span x-text="c.nameAr" class="text-xs sm:text-sm text-gray-500"></span>
                    </span>
                    <span x-text="c.code" class="text-xs sm:text-sm text-gray-400 font-bold" dir="ltr"></span>
                </button>
            </template>
        </div>
    </div>
</div>
