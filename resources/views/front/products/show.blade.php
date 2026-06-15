@extends('layouts.app')

@section('title', $product->name . ' - لحمكس')

@section('content')
<div class="py-12 bg-bg-custom min-h-screen" x-data="{ quantity: 1, stock: {{ $product->stock_quantity }} }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Product Card Details -->
        <div class="bg-white rounded-3xl border border-red-50 shadow-sm overflow-hidden grid grid-cols-1 md:grid-cols-2 gap-12 p-8 md:p-12 mb-16">
            
            <!-- Image Panel -->
            <div class="bg-red-50/40 rounded-2xl flex items-center justify-center h-96 md:h-[500px] border border-red-50 overflow-hidden relative">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                @else
                    <span class="text-9xl">🥩</span>
                @endif
                @if($product->discount_price)
                    <span class="absolute top-6 right-6 bg-secondary text-white px-4 py-2 rounded-full text-sm font-extrabold shadow-md">خصم مميز</span>
                @endif
            </div>

            <!-- Content Panel -->
            <div class="flex flex-col justify-between">
                <div>
                    <span class="text-accent font-extrabold text-sm uppercase tracking-wider block mb-2">{{ $product->category->name }}</span>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-text-custom mb-4 leading-snug">{{ $product->name }}</h1>
                    
                    <!-- Weight Badge -->
                    @if($product->weight)
                        <span class="inline-block bg-red-50 text-secondary border border-red-100 rounded-lg px-3 py-1 text-sm font-bold mb-6">
                            الوزن التقريبي: {{ $product->weight }} كجم
                        </span>
                    @endif

                    <!-- Pricing -->
                    <div class="mb-6 flex items-baseline gap-4">
                        @if($product->discount_price)
                            <span class="text-4xl font-extrabold text-primary">{{ $product->discount_price }} ر.س</span>
                            <span class="text-lg text-gray-400 line-through">{{ $product->price }} ر.س</span>
                        @else
                            <span class="text-4xl font-extrabold text-primary">{{ $product->price }} ر.س</span>
                        @endif
                    </div>

                    <!-- Description -->
                    <div class="border-t border-red-50 pt-6 mb-8">
                        <h3 class="text-lg font-bold text-text-custom mb-3">تفاصيل المنتج</h3>
                        <p class="text-gray-600 leading-relaxed">{{ $product->description ?? 'لا يوجد وصف متاح للمنتج حالياً.' }}</p>
                    </div>
                </div>

                <!-- Action Form -->
                <div>
                    @if($product->stock_quantity > 0)
                        <div class="flex items-center gap-6 mb-6">
                            <span class="text-sm font-bold text-text-custom">الكمية:</span>
                            <div class="flex items-center border border-red-100 rounded-xl overflow-hidden bg-white">
                                <button type="button" @click="if (quantity < stock) quantity++" class="px-4 py-2.5 bg-red-50 font-bold hover:bg-red-100 transition text-lg">+</button>
                                <span class="px-6 py-2.5 font-extrabold text-lg" x-text="quantity"></span>
                                <button type="button" @click="if (quantity > 1) quantity--" class="px-4 py-2.5 bg-red-50 font-bold hover:bg-red-100 transition text-lg">-</button>
                            </div>
                            <span class="text-xs text-gray-400">(المخزون المتوفر: {{ $product->stock_quantity }} قطع)</span>
                        </div>

                        <button @click="addToCartWithQuantity({{ $product->id }})" 
                                class="w-full bg-primary hover:bg-primary-dark text-white font-extrabold py-4 rounded-2xl transition shadow-lg flex items-center justify-center gap-3 text-lg">
                            <span>🛒</span> إضافة لسلة المشتريات
                        </button>
                    @else
                        <div class="bg-red-50 border border-red-100 text-red-700 font-bold p-4 rounded-xl text-center mb-6">
                            ⚠️ نفذت الكمية من المخزون حالياً
                        </div>
                        <button disabled class="w-full bg-gray-200 text-gray-400 font-bold py-4 rounded-2xl cursor-not-allowed text-center text-lg">
                            غير متوفر
                        </button>
                    @endif
                </div>

            </div>
        </div>

        <!-- Related Products -->
        @if(!$relatedProducts->isEmpty())
            <div>
                <h2 class="text-2xl font-extrabold text-primary mb-8 border-r-4 border-primary pr-3">منتجات مشابهة قد تعجبك</h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $rel)
                        <div class="bg-white rounded-2xl overflow-hidden border border-red-50 hover:shadow-sm transition flex flex-col justify-between">
                            <div class="h-48 bg-red-50/40 relative">
                                @if($rel->image)
                                    <img src="{{ asset('storage/' . $rel->image) }}" alt="{{ $rel->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-5xl">🥩</div>
                                @endif
                            </div>
                            <div class="p-5">
                                <a href="{{ route('products.show', $rel->slug) }}" class="font-bold text-text-custom hover:text-primary transition block mb-2">{{ $rel->name }}</a>
                                <span class="text-lg font-extrabold text-primary">{{ $rel->discount_price ?? $rel->price }} ر.س</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>
@endsection

@section('scripts')
<script>
    function addToCartWithQuantity(productId) {
        // Retrieve quantity from Alpine component
        const el = document.querySelector('[x-data]');
        const quantity = el ? el.__x.$data.quantity : 1;

        fetch('{{ route('cart.add') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
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
