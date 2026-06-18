@props(['product'])

{{-- Pass initial cart quantity for this product (0 if not in cart) --}}
@php
    $cartQty = 0;
    try {
        $cartRepo = app(\App\Repositories\CartRepository::class);
        $cart = $cartRepo->getOrCreateCart();
        $cartItem = $cart->items()->where('product_id', $product->id)->first();
        $cartQty = $cartItem ? $cartItem->quantity : 0;
    } catch (\Throwable $e) {
        $cartQty = 0;
    }
@endphp

<div class="bg-white rounded-[28px] overflow-hidden border border-gray-100 shadow-xs hover:shadow-lg hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between h-full bg-[#FAF9F6]/20"
     x-data="productCard({ productId: {{ $product->id }}, initialQty: {{ $cartQty }}, isWished: window.Wishlist ? window.Wishlist.has({{ $product->id }}) : false })"
     id="product-card-{{ $product->id }}">
    
    {{-- Image Container --}}
    <div class="h-64 relative overflow-hidden bg-gray-50">
        @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover select-none">
        @else
            <div class="w-full h-full flex items-center justify-center text-7xl select-none bg-red-50/20">🥩</div>
        @endif
        
        {{-- Round Badge: شامل الذبح والتوصيل --}}
        <div class="absolute bottom-4 right-4 w-16 h-16 rounded-full bg-[#1b3d2e]/90 border border-white/20 text-white flex flex-col items-center justify-center text-[9px] font-bold text-center leading-tight shadow-md select-none">
            <span>شامل</span>
            <span>الذبح</span>
            <span>والتوصيل</span>
        </div>

        {{-- Wishlist Heart Button --}}
        <button type="button"
                class="absolute top-3 left-3 z-10 w-9 h-9 rounded-full shadow-md flex items-center justify-center border hover:scale-110 active:scale-95 transition-all duration-200 select-none"
                :class="isWished ? 'bg-red-50 border-red-200' : 'bg-white/90 border-white/60'"
                @click.stop="toggleWish($el)"
                aria-label="إضافة للمفضلة">
            <i class="fa-heart text-base transition-colors duration-200"
               :class="isWished ? 'fa-solid text-red-500' : 'fa-regular text-gray-400'"></i>
        </button>

        {{-- Cart Quantity Badge (shows if qty > 0) --}}
        <div x-show="cartQty > 0"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-75"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-75"
             class="absolute top-3 left-14 min-w-[28px] h-7 px-1.5 rounded-full bg-[#7A0C0C] border-2 border-white text-white text-xs font-black flex items-center justify-center shadow-lg select-none z-10">
            <span x-text="cartQty + ' 🛒'"></span>
        </div>
    </div>
    
    {{-- Content --}}
    <div class="p-5 flex-1 flex flex-col justify-between">
        
        {{-- Title --}}
        <div class="text-center mb-4">
            <h3 class="text-lg font-extrabold text-[#1b3d2e] leading-snug line-clamp-2 mb-1">{{ $product->name }}</h3>
        </div>

        {{-- Specifications Section --}}
        <div class="space-y-2.5 border-t border-b border-gray-100/70 py-4 mb-4 text-right">
            
            {{-- Price Before Discount --}}
            <div class="flex items-center justify-between text-xs">
                <div class="flex items-center gap-2 text-gray-500">
                    <span class="w-5 h-5 rounded-full bg-gray-50 flex items-center justify-center text-gray-400">🏷️</span>
                    <span class="font-bold text-gray-500/80">السعر قبل الخصم</span>
                </div>
                <span class="font-bold text-gray-400 line-through">{{ number_format($product->price) }} ر.س</span>
            </div>

            {{-- Price After Discount --}}
            <div class="flex items-center justify-between text-xs">
                <div class="flex items-center gap-2 text-gray-600">
                    <span class="w-5 h-5 rounded-full bg-red-50 flex items-center justify-center text-red-500">🔥</span>
                    <span class="font-bold text-gray-700">السعر بعد الخصم</span>
                </div>
                <span class="font-extrabold text-[#7A0C0C]">
                    {{ number_format($product->discount_price ?? $product->price) }} ر.س
                </span>
            </div>

            {{-- Delivery --}}
            <div class="flex items-center justify-between text-xs">
                <div class="flex items-center gap-2 text-gray-600">
                    <span class="w-5 h-5 rounded-full bg-amber-50 flex items-center justify-center text-amber-500">📅</span>
                    <span class="font-bold text-gray-700">التوصيل</span>
                </div>
                <span class="font-bold text-gray-600">في أول يوم العيد</span>
            </div>

            {{-- Weight --}}
            <div class="flex items-center justify-between text-xs">
                <div class="flex items-center gap-2 text-gray-600">
                    <span class="w-5 h-5 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500">⚖️</span>
                    <span class="font-bold text-gray-700">الوزن</span>
                </div>
                <span class="font-bold text-gray-600">
                    {{ $product->weight ? round($product->weight) . ' - ' . (round($product->weight) + 3) : '22 - 25' }} كيلو
                </span>
            </div>
            
        </div>

        {{-- Footer Details & Button --}}
        <div>
            {{-- Big Price --}}
            <div class="text-center mb-4">
                <span class="text-2xl font-black text-[#1b3d2e]">{{ number_format($product->discount_price ?? $product->price) }} ر.س</span>
                <span class="block text-[10px] text-gray-400 font-bold mt-0.5">شامل الضريبة</span>
            </div>

            {{-- Action Button --}}
            <a href="{{ route('products.show', $product->slug) }}" 
               class="w-full bg-[#1b3d2e] hover:bg-[#122a20] text-white font-extrabold py-3.5 rounded-xl transition-all duration-200 flex items-center justify-center gap-2 text-sm text-center shadow-sm hover:shadow-md cursor-pointer active:scale-[0.98]">
                <i class="fa-solid fa-cart-shopping text-xs"></i>
                <span>اختر</span>
            </a>
        </div>
        
    </div>
</div>
