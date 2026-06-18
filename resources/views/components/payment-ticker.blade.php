<!-- Payment Methods Ticker -->
<div class="w-full bg-white border-y border-red-50/50 py-5 overflow-hidden relative select-none">
    <!-- Gradient overlays for smooth fading edges -->
    <div class="absolute inset-y-0 right-0 w-16 bg-gradient-to-l from-white to-transparent z-10 pointer-events-none"></div>
    <div class="absolute inset-y-0 left-0 w-16 bg-gradient-to-r from-white to-transparent z-10 pointer-events-none"></div>

    <!-- Ticker Label -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-3 flex items-center justify-between">
        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">طرق دفع آمنة وسهلة</span>
        <span class="h-[1px] bg-red-100 flex-grow mx-4"></span>
    </div>

    @php
    $paymentLogos = [
        'https://res.cloudinary.com/dmma4cjad/image/upload/v1781775867/images_qkc2e2.jpg',
        'https://res.cloudinary.com/dmma4cjad/image/upload/v1781775590/download_orcw6j.jpg',
        'https://res.cloudinary.com/dmma4cjad/image/upload/v1781775784/card_kefknb.png',
        'https://res.cloudinary.com/dmma4cjad/image/upload/v1781775577/download_tzxo6z.png',
    ];
    // Repeat logos 5 times to make it a long seamless row
    $repeatedLogos = array_merge($paymentLogos, $paymentLogos, $paymentLogos, $paymentLogos, $paymentLogos);
    @endphp

    <!-- Sliding Content (Ltr for consistent infinite scroll physics) -->
    <div dir="ltr" class="flex overflow-hidden select-none gap-12">
        <!-- List 1 -->
        <div class="flex shrink-0 items-center justify-around gap-12 animate-[marquee_20s_linear_infinite] flex-row min-w-full">
            @foreach($repeatedLogos as $logo)
                <div class="flex items-center justify-center px-4">
                    <img src="{{ $logo }}" alt="Payment Method" class="h-7 max-w-[90px] sm:h-8 sm:max-w-[100px] object-contain opacity-80 hover:opacity-100 transition-opacity duration-200">
                </div>
            @endforeach
        </div>

        <!-- List 2 (Hidden duplicate for seamless sliding) -->
        <div aria-hidden="true" class="flex shrink-0 items-center justify-around gap-12 animate-[marquee_20s_linear_infinite] flex-row min-w-full">
            @foreach($repeatedLogos as $logo)
                <div class="flex items-center justify-center px-4">
                    <img src="{{ $logo }}" alt="Payment Method" class="h-7 max-w-[90px] sm:h-8 sm:max-w-[100px] object-contain opacity-80 hover:opacity-100 transition-opacity duration-200">
                </div>
            @endforeach
        </div>
    </div>
</div>
