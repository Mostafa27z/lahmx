@extends('layouts.app')

@section('title', 'جميع منتجاتنا - لحمكس')

@section('content')
<div class="py-12 bg-bg-custom min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Page Title & Search Form -->
        <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-12 bg-white p-6 rounded-2xl border border-red-50 shadow-sm">
            <div>
                <h1 class="text-3xl font-extrabold text-primary">قائمة المنتجات</h1>
                <p class="text-gray-500 mt-1">تصفح أنواع اللحوم الطازجة والقطعيات الفاخرة</p>
            </div>
            
            <form action="{{ route('products.index') }}" method="GET" class="w-full md:w-auto flex items-center gap-2">
                @if($categoryId)
                    <input type="hidden" name="category_id" value="{{ $categoryId }}">
                @endif
                <input type="text" name="search" placeholder="ابحث عن منتج..." value="{{ $search }}"
                       class="w-full md:w-80 px-4 py-3 rounded-lg border border-red-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-right">
                <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-bold px-6 py-3 rounded-lg transition duration-150">بحث</button>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar Filters -->
            <div class="bg-white p-6 rounded-2xl border border-red-50 shadow-sm h-fit">
                <h3 class="text-lg font-bold text-primary mb-4 pb-2 border-b border-red-50">تصفية حسب الفئة</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('products.index', ['search' => $search]) }}" 
                           class="flex justify-between items-center px-3 py-2 rounded-lg font-semibold transition {{ is_null($categoryId) ? 'bg-primary text-white' : 'text-text-custom hover:bg-red-50' }}">
                            <span>الكل</span>
                            <span>📦</span>
                        </a>
                    </li>
                    @foreach($categories as $category)
                        <li>
                            <a href="{{ route('products.index', ['category_id' => $category->id, 'search' => $search]) }}" 
                               class="flex justify-between items-center px-3 py-2 rounded-lg font-semibold transition {{ $categoryId == $category->id ? 'bg-primary text-white' : 'text-text-custom hover:bg-red-50' }}">
                                <span>{{ $category->name }}</span>
                                <span>🥩</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Products Listing -->
            <div class="lg:col-span-3">
                @if($products->isEmpty())
                    <div class="bg-white rounded-2xl p-12 text-center border border-red-50 shadow-sm">
                        <span class="text-6xl block mb-4">🔍</span>
                        <h3 class="text-xl font-bold text-text-custom mb-2">عذراً، لم نجد أي منتجات تطابق بحثك.</h3>
                        <p class="text-gray-400">حاول البحث بكلمات أخرى أو اختر فئة مختلفة.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <div class="bg-white rounded-2xl overflow-hidden border border-red-50 hover:shadow-md transition flex flex-col justify-between">
                                <div class="h-56 bg-red-50/40 relative">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-6xl">🥩</div>
                                    @endif
                                    @if($product->discount_price)
                                        <span class="absolute top-4 right-4 bg-secondary text-white px-3 py-1 rounded-full text-xs font-bold">خصم مميز</span>
                                    @endif
                                </div>
                                
                                <div class="p-5 flex-1 flex flex-col justify-between">
                                    <div>
                                        <span class="text-accent text-xs font-bold block mb-1">{{ $product->category->name }}</span>
                                        <a href="{{ route('products.show', $product->slug) }}" class="text-lg font-bold text-text-custom hover:text-primary transition block mb-2">{{ $product->name }}</a>
                                        <p class="text-gray-500 text-xs line-clamp-2 mb-4">{{ $product->description }}</p>
                                    </div>

                                    <div>
                                        <div class="flex items-center justify-between mb-4">
                                            <div>
                                                @if($product->discount_price)
                                                    <span class="text-xl font-extrabold text-primary">{{ $product->discount_price }} ر.س</span>
                                                    <span class="text-xs text-gray-400 line-through mr-2">{{ $product->price }} ر.س</span>
                                                @else
                                                    <span class="text-xl font-extrabold text-primary">{{ $product->price }} ر.س</span>
                                                @endif
                                                @if($product->weight)
                                                    <span class="text-xs text-gray-400 block mt-1">الوزن: {{ $product->weight }} كجم</span>
                                                @endif
                                            </div>
                                        </div>

                                        <button @click="addToCart({{ $product->id }})" 
                                                class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-2 rounded-xl transition flex items-center justify-center gap-2 text-sm">
                                            <span>🛒</span> إضافة للسلة
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-12">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
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
