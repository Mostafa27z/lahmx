@extends('layouts.app')

@section('title', 'من نحن - لحمكس للذبائح الطازجة')

@section('content')

{{-- Main Content Container --}}
<div class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Clean Title Section --}}
        <div class="text-right mb-10 border-b border-gray-100 pb-6">
            <h1 class="text-3xl md:text-4xl font-extrabold text-primary">من نحن</h1>
            <p class="text-gray-500 mt-2">لحمكس للذبائح الطازجة — جودة لا تُقبل على تنازل، وأمانة لا تُساوم عليها</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">

            {{-- Image Column --}}
            <div class="relative order-2 md:order-1">
                <div class="absolute inset-0 bg-red-50 rounded-3xl transform -rotate-3 scale-95"></div>
                <img src="{{ asset('images/about_pasture.png') }}"
                     alt="مراعي لحمكس"
                     class="relative z-10 w-full h-[320px] sm:h-[440px] object-cover rounded-3xl shadow-xl border-4 border-white hover:scale-[1.02] transition-transform duration-300">
            </div>

            {{-- Content Column --}}
            <div class="text-right order-1 md:order-2 space-y-6">
                <h2 class="text-2xl md:text-3xl font-extrabold text-primary">لحمكس للذبائح الطازجة</h2>

                <p class="text-gray-600 leading-relaxed text-base">
                    نحن في <span class="font-bold text-primary">لحمكس</span> نؤمن بأن الجودة ليست مجرد شعار، بل هي التزام وأمانة.
                    تبدأ رحلتنا من اختيار أجود سلالات المواشي ورعايتها رعاية صحية متكاملة تحت إشراف نخبة من الأطباء البيطريين
                    وأخصائيي التغذية في مزارعنا الشريكة.
                </p>

                <p class="text-gray-600 leading-relaxed text-base">
                    نحرص على تقديم اللحوم الطازجة والذبائح اليومية التي تُذبح وتُجهز في مسالخ معتمدة ووفق الشريعة الإسلامية،
                    مع تغليفها وتبريدها بأحدث الأنظمة للحفاظ على طعمها الأصيل وطراوتها حتى تصل إلى باب منزلك
                    بأعلى معايير الأمان والسلامة الغذائية.
                </p>

                {{-- Checkmarks --}}
                <div class="space-y-4 pt-2">
                    @foreach([
                        'مواشي بلدية 100% مغذاة بأعلاف طبيعية',
                        'نحر وتجهيز يومي بأحدث التقنيات والتعقيم',
                        'تغليف آمن بسحب الهواء وتوصيل مبرد متكامل',
                        'إشراف بيطري متخصص على كل مراحل التربية',
                        'مسالخ معتمدة وفق الشريعة الإسلامية',
                    ] as $item)
                    <div class="flex items-center gap-3">
                        <span class="w-6 h-6 rounded-full bg-primary flex items-center justify-center text-white text-xs font-bold shrink-0 shadow-sm">✓</span>
                        <span class="font-bold text-gray-700 text-sm md:text-base">{{ $item }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- Simple Call to Action (CTA) without full background --}}
        <div class="mt-20 pt-10 border-t border-gray-100 text-center">
            <h3 class="text-xl md:text-2xl font-bold text-primary mb-3">مستعد للطلب؟</h3>
            <p class="text-gray-500 mb-6 max-w-md mx-auto">اطلب أفضل الذبائح الطازجة مباشرة إلى باب منزلك الآن</p>
            <a href="{{ route('products.index') }}"
               class="inline-block bg-primary text-white font-bold px-8 py-3.5 rounded-xl shadow-md hover:bg-primary-dark hover:shadow-lg transition duration-200">
                تصفح منتجاتنا الآن
            </a>
        </div>

    </div>
</div>

@endsection
