@extends('layouts.app')

@section('title', 'لحمكس - متجر اللحوم الطازجة والفاخرة')

@section('content')
<!-- Hero Carousel Slider -->
<x-hero />

<!-- Categories Slider (Livestock Section) -->
<div id="categories" class="py-12 bg-white border-b border-gray-100 scroll-mt-20"
     x-data="{
        currentIndex: 0,
        totalItems: {{ $categories->count() }},
        visibleItems: 4,
        timer: null,
        updateVisibleItems() {
            if (window.innerWidth >= 1024) {
                this.visibleItems = 4;
            } else if (window.innerWidth >= 768) {
                this.visibleItems = 3;
            } else {
                this.visibleItems = 2;
            }
        },
        startAutoPlay() {
            if (this.totalItems > 4) {
                this.timer = setInterval(() => {
                    this.next();
                }, 3000);
            }
        },
        stopAutoPlay() {
            if (this.timer) {
                clearInterval(this.timer);
                this.timer = null;
            }
        },
        next() {
            let maxIndex = this.totalItems - this.visibleItems;
            if (maxIndex <= 0) return;
            if (this.currentIndex >= maxIndex) {
                this.currentIndex = 0;
            } else {
                this.currentIndex++;
            }
        },
        prev() {
            let maxIndex = this.totalItems - this.visibleItems;
            if (maxIndex <= 0) return;
            if (this.currentIndex <= 0) {
                this.currentIndex = maxIndex;
            } else {
                this.currentIndex--;
            }
        },
        init() {
            this.updateVisibleItems();
            window.addEventListener('resize', () => {
                this.updateVisibleItems();
                let maxIndex = this.totalItems - this.visibleItems;
                if (this.currentIndex > maxIndex) {
                    this.currentIndex = Math.max(0, maxIndex);
                }
            });
            this.startAutoPlay();
        }
     }"
     @mouseenter="stopAutoPlay()"
     @mouseleave="startAutoPlay()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="flex justify-between items-center mb-8">
            <div class="text-right">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#1b3d2e]">أقسام المتجر (المواشي)</h2>
                <p class="text-gray-500 mt-1 text-sm sm:text-base">اختر الفئة المفضلة لديك وتصفح أفضل المنتجات</p>
            </div>
            
            <!-- Custom Navigation Arrows (Only show/enable if totalItems > visibleItems) -->
            <template x-if="totalItems > visibleItems">
                <div class="flex items-center gap-2" dir="ltr">
                    <button @click="prev()" 
                            class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-red-50 hover:bg-primary text-primary hover:text-white flex items-center justify-center transition-all duration-200 cursor-pointer shadow-sm active:scale-95">
                        <i class="fa-solid fa-chevron-left text-xs sm:text-sm"></i>
                    </button>
                    <button @click="next()" 
                            class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-red-50 hover:bg-primary text-primary hover:text-white flex items-center justify-center transition-all duration-200 cursor-pointer shadow-sm active:scale-95">
                        <i class="fa-solid fa-chevron-right text-xs sm:text-sm"></i>
                    </button>
                </div>
            </template>
        </div>

        <!-- Carousel Window -->
        <div class="overflow-hidden w-full rounded-2xl sm:rounded-3xl">
            <!-- LTR container for translation logic -->
            <div dir="ltr" class="flex transition-transform duration-500 ease-in-out -mx-2 sm:-mx-3"
                 :style="`transform: translateX(-${currentIndex * (100 / visibleItems)}%);`"
                 style="width: 100%;">
                 
                @foreach($categories as $category)
                    <div class="flex-shrink-0 px-2 sm:px-3 w-1/2 md:w-1/3 lg:w-1/4" dir="rtl">
                        <a href="{{ route('products.index', ['category_id' => $category->id]) }}" 
                           class="group block bg-white rounded-2xl sm:rounded-3xl overflow-hidden border border-gray-100 shadow-xs hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 h-72 sm:h-80 flex flex-col">
                            
                            <!-- Image 3/4 (75%) -->
                            <div class="h-[75%] w-full relative overflow-hidden bg-gray-50/20">
                                @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" 
                                         alt="{{ $category->name }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 select-none">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-50/30 text-5xl select-none">
                                        🥩
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Content/Title 1/4 (25%) -->
                            <div class="h-[25%] w-full flex items-center justify-center bg-white px-4 border-t border-gray-50/50">
                                <h3 class="font-extrabold text-[#1b3d2e] text-base sm:text-lg text-center truncate group-hover:text-primary transition-colors duration-200">
                                    {{ $category->name }}
                                </h3>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Decorative Banner above Products Section -->
<div class="w-full overflow-hidden">
    <img src="https://res.cloudinary.com/dmma4cjad/image/upload/v1781788497/f28ab8c7-4f4e-410d-843c-8736a27c3997_a6baft.png"
         alt="لحمكس - اختيارك الأول للحوم الطازجة في المملكة"
         class="w-full block">
</div>

<!-- Featured Products ("منتجاتنا" Section) -->
<div id="products-section" class="py-16 bg-white scroll-mt-20 border-b border-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-extrabold text-[#1b3d2e]">منتجاتنا</h2>
            <p class="text-gray-500 mt-2">نقدم لكم أفضل أنواع الذبائح الطازجة يومياً</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredProducts as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>

        <!-- View All Products CTA Button -->
        <div class="text-center mt-12">
            <a href="{{ route('products.index') }}"
               class="inline-block bg-[#1b3d2e] hover:bg-[#122a20] text-white font-extrabold px-10 py-4 rounded-xl shadow-md hover:shadow-lg transition duration-200">
                الاطلاع على جميع المنتجات 🥩
            </a>
        </div>
    </div>
</div>

<!-- Core Features Section -->
<div class="py-4 bg-[#FAF9F6]/40">
    <x-features />
</div>

<!-- Payment Methods Ticker -->
<div class="border-t border-gray-100">
    <x-payment-ticker />
</div>

<!-- Contact Anchor for nav -->
<div id="contact" class="scroll-mt-20"></div>

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
