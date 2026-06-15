@extends('layouts.app')

@section('title', 'لحمكس - متجر اللحوم الطازجة والفاخرة')

@section('content')
<!-- Hero Banner -->
<div class="relative bg-primary-dark text-white overflow-hidden py-24 px-8 border-b-8 border-accent">
    <!-- Overlay/Decorative elements -->
    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#FFF8F6_1px,transparent_1px)] [background-size:16px_16px]"></div>
    
    <div class="max-w-7xl mx-auto relative z-10 flex flex-col md:flex-row items-center justify-between gap-12">
        <div class="max-w-2xl text-right">
            <span class="inline-block bg-accent text-primary-dark font-extrabold px-4 py-1.5 rounded-full text-sm mb-6">🥩 لحوم بلدية 100% طازجة</span>
            <h1 class="text-4xl md:text-6xl font-extrabold leading-tight mb-6">
                استمتع بالطعم الحقيقي للحم الفاخر
            </h1>
            <p class="text-red-100 text-lg md:text-xl mb-8 leading-relaxed">
                نقدم لك تشكيلة فاخرة من اللحوم الطازجة، مقطعة ومغلفة بعناية تامة لتصلك طازجة أينما كنت.
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('products.index') }}" class="bg-accent text-primary-dark hover:bg-white transition duration-150 px-8 py-4 rounded-xl font-extrabold text-lg shadow-lg">تسوق الآن 🛍️</a>
                <a href="#categories" class="bg-transparent border-2 border-white hover:bg-white hover:text-primary transition duration-150 px-8 py-4 rounded-xl font-extrabold text-lg">تصفح الفئات 📁</a>
            </div>
        </div>
        
        <div class="relative w-80 h-80 bg-red-950 rounded-full flex items-center justify-center border-4 border-accent shadow-2xl">
            <span class="text-9xl">🥩</span>
        </div>
    </div>
</div>

<!-- Categories Grid -->
<div id="categories" class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
        <h2 class="text-3xl font-extrabold text-primary">أقسام المتجر</h2>
        <p class="text-gray-500 mt-2">اختر الفئة المفضلة لديك وتصفح أفضل المنتجات</p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
        @foreach($categories as $category)
            <a href="{{ route('products.index', ['category_id' => $category->id]) }}" 
               class="bg-white rounded-2xl p-6 border border-red-50 hover:border-accent hover:shadow-lg transition duration-200 text-center flex flex-col items-center">
                <div class="w-20 h-20 rounded-full bg-red-50 flex items-center justify-center text-4xl mb-4">
                    @if($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-full h-full object-cover rounded-full">
                    @else
                        🥩
                    @endif
                </div>
                <h3 class="font-extrabold text-text-custom text-lg">{{ $category->name }}</h3>
            </a>
        @endforeach
    </div>
</div>

<!-- Featured Products -->
<div class="py-16 bg-red-50/30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-12">
            <div>
                <h2 class="text-3xl font-extrabold text-primary">المنتجات الأكثر مبيعاً 🌟</h2>
                <p class="text-gray-500 mt-2">توصياتنا المميزة لأجلك</p>
            </div>
            <a href="{{ route('products.index') }}" class="text-secondary hover:text-primary font-bold transition">عرض الكل &larr;</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($featuredProducts as $product)
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-red-50 hover:shadow-md transition flex flex-col">
                    <div class="h-64 bg-red-50/40 relative">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-8xl">🥩</div>
                        @endif
                        @if($product->discount_price)
                            <span class="absolute top-4 right-4 bg-secondary text-white px-3 py-1 rounded-full text-xs font-bold">خصم مميز</span>
                        @endif
                    </div>
                    
                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div>
                            <span class="text-accent text-sm font-bold block mb-1">{{ $product->category->name }}</span>
                            <a href="{{ route('products.show', $product->slug) }}" class="text-xl font-bold text-text-custom hover:text-primary transition block mb-2">{{ $product->name }}</a>
                            <p class="text-gray-500 text-sm line-clamp-2 mb-4">{{ $product->description }}</p>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    @if($product->discount_price)
                                        <span class="text-2xl font-extrabold text-primary">{{ $product->discount_price }} ر.س</span>
                                        <span class="text-sm text-gray-400 line-through mr-2">{{ $product->price }} ر.س</span>
                                    @else
                                        <span class="text-2xl font-extrabold text-primary">{{ $product->price }} ر.س</span>
                                    @endif
                                    @if($product->weight)
                                        <span class="text-xs text-gray-400 block mt-1">الوزن التقريبي: {{ $product->weight }} كجم</span>
                                    @endif
                                </div>
                            </div>

                            <button @click="addToCart({{ $product->id }})" 
                                    class="w-full bg-primary hover:bg-primary-dark text-white font-extrabold py-3 rounded-xl transition flex items-center justify-center gap-2">
                                <span>🛒</span> إضافة للسلة
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="text-center p-8 bg-white border border-red-50 rounded-2xl shadow-sm">
            <span class="text-5xl block mb-4">🚚</span>
            <h3 class="text-xl font-extrabold text-primary mb-2">توصيل مبرد وسريع</h3>
            <p class="text-gray-500 text-sm">نصلك في سيارات مجهزة بنظام تبريد متكامل للحفاظ على جودة اللحوم وطراوتها.</p>
        </div>
        <div class="text-center p-8 bg-white border border-red-50 rounded-2xl shadow-sm">
            <span class="text-5xl block mb-4">🛡️</span>
            <h3 class="text-xl font-extrabold text-primary mb-2">أعلى معايير النظافة</h3>
            <p class="text-gray-500 text-sm">تقطيع وتغليف آلي تحت إشراف أخصائيي الجودة والسلامة الغذائية.</p>
        </div>
        <div class="text-center p-8 bg-white border border-red-50 rounded-2xl shadow-sm">
            <span class="text-5xl block mb-4">💳</span>
            <h3 class="text-xl font-extrabold text-primary mb-2">طرق دفع مرنة</h3>
            <p class="text-gray-500 text-sm">ادفع بأمان عبر بطاقة فيزا الائتمانية أو بالتقسيط عبر تابي وتمارا.</p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function addToCart(productId) {
        fetch('{{ route('cart.add') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update cart count globally
                const AlpineBody = document.querySelector('body');
                if (AlpineBody && AlpineBody.__x) {
                    AlpineBody.__x.$data.cartCount = data.items_count;
                }
                
                Swal.fire({
                    icon: 'success',
                    title: 'نجاح',
                    text: data.message,
                    confirmButtonText: 'إكمال التسوق',
                    showCancelButton: true,
                    cancelButtonText: 'ذهاب إلى السلة 🛒',
                    confirmButtonColor: '#7A0C0C',
                    cancelButtonColor: '#B22222'
                }).then((result) => {
                    if (result.isDismissed) {
                        window.location.href = '{{ route('cart.index') }}';
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: data.message || 'حدث خطأ ما',
                    confirmButtonColor: '#7A0C0C'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: 'الرجاء المحاولة مرة أخرى.',
                confirmButtonColor: '#7A0C0C'
            });
        });
    }
</script>
@endsection
