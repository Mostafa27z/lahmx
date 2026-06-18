@extends('layouts.app')

@section('title', 'المفضلة - لحمكس')

@section('content')
<div class="min-h-screen bg-[#FAF7F4] py-10"
     x-data="{
         products: [],
         loading: true,
         async init() {
             const ids = window.Wishlist ? window.Wishlist.all() : [];
             if (!ids.length) { this.loading = false; return; }
             try {
                 const res = await fetch('{{ route('wishlist.fetch') }}', {
                     method: 'POST',
                     headers: {
                         'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                         'Accept': 'application/json'
                     },
                     body: JSON.stringify({ ids })
                 });
                 this.products = await res.json();
             } catch(e) { this.products = []; }
             this.loading = false;
         },
         remove(productId) {
             if (window.Wishlist) window.Wishlist.remove(productId);
             this.products = this.products.filter(p => p.id !== productId);
             // Update navbar
             const body = document.querySelector('[x-data]');
             if (body && body._x_dataStack && body._x_dataStack[0]) {
                 body._x_dataStack[0].wishlistCount = window.Wishlist.count();
             }
             window.showToast && window.showToast('تمت إزالة المنتج من المفضلة', 'info');
         }
     }"
     x-init="init()">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Page Header --}}
        <div class="flex items-center gap-4 mb-8">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center" style="background:#fef2f2;">
                <i class="fa-solid fa-heart text-xl" style="color:#7a0c0c;"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black text-gray-900" style="font-family:'Cairo',sans-serif;">قائمة المفضلة</h1>
                <p class="text-gray-400 text-sm font-semibold" x-text="products.length ? products.length + ' منتج محفوظ' : ''"></p>
            </div>
        </div>

        {{-- Loading Skeleton --}}
        <template x-if="loading">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <template x-for="i in 4" :key="i">
                    <div class="bg-white rounded-3xl overflow-hidden border border-gray-100 animate-pulse">
                        <div class="h-56 bg-gray-100"></div>
                        <div class="p-5 space-y-3">
                            <div class="h-4 bg-gray-100 rounded-full w-3/4 mx-auto"></div>
                            <div class="h-6 bg-gray-100 rounded-full w-1/2 mx-auto"></div>
                            <div class="h-10 bg-gray-100 rounded-xl"></div>
                        </div>
                    </div>
                </template>
            </div>
        </template>

        {{-- Empty State --}}
        <template x-if="!loading && products.length === 0">
            <div class="flex flex-col items-center justify-center py-24 text-center">
                <div class="w-24 h-24 rounded-full flex items-center justify-center mb-6" style="background:#fef2f2;">
                    <i class="fa-regular fa-heart text-4xl" style="color:#7a0c0c; opacity:0.4;"></i>
                </div>
                <h2 class="text-xl font-extrabold text-gray-700 mb-2" style="font-family:'Cairo',sans-serif;">قائمة المفضلة فارغة</h2>
                <p class="text-gray-400 text-sm mb-8">أضف المنتجات اللي تعجبك عن طريق أيقونة ❤️ على كل منتج</p>
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center gap-2 text-white font-extrabold px-8 py-3 rounded-xl text-sm transition-all duration-200 hover:opacity-90"
                   style="background:#7a0c0c;">
                    <i class="fa-solid fa-bag-shopping"></i>
                    تصفح المنتجات
                </a>
            </div>
        </template>

        {{-- Products Grid --}}
        <template x-if="!loading && products.length > 0">
            <div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <template x-for="product in products" :key="product.id">
                        <div class="bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 flex flex-col">

                            {{-- Image --}}
                            <div class="relative h-56 bg-[#FAF7F4] overflow-hidden flex items-center justify-center">
                                <a :href="product.product_url" class="block w-full h-full">
                                    <template x-if="product.image_url">
                                        <img :src="product.image_url" :alt="product.name"
                                             class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                                    </template>
                                    <template x-if="!product.image_url">
                                        <div class="w-full h-full flex items-center justify-center text-6xl">🥩</div>
                                    </template>
                                </a>

                                {{-- Remove button --}}
                                <button type="button"
                                        @click="remove(product.id)"
                                        class="absolute top-3 left-3 w-9 h-9 rounded-full bg-red-50 border border-red-200 flex items-center justify-center shadow-md hover:bg-red-100 hover:scale-110 active:scale-95 transition-all duration-200">
                                    <i class="fa-solid fa-heart text-red-500 text-base"></i>
                                </button>

                                {{-- Out of stock badge --}}
                                <template x-if="!product.in_stock">
                                    <span class="absolute bottom-3 right-3 text-xs font-bold px-3 py-1 rounded-full text-white"
                                          style="background:#7a0c0c;">نفذت الكمية</span>
                                </template>
                            </div>

                            {{-- Info --}}
                            <div class="p-5 flex-1 flex flex-col justify-between">
                                <div class="text-center mb-4">
                                    <a :href="product.product_url"
                                       class="text-lg font-extrabold text-gray-800 leading-snug hover:text-[#7a0c0c] transition-colors"
                                       style="font-family:'Cairo',sans-serif;"
                                       x-text="product.name"></a>
                                    <template x-if="product.weight">
                                        <p class="text-gray-400 text-xs font-semibold mt-1"
                                           x-text="'الوزن: ' + product.weight + ' كجم'"></p>
                                    </template>
                                </div>

                                {{-- Price --}}
                                <div class="text-center mb-4">
                                    <span class="text-2xl font-black" style="color:#7a0c0c;"
                                          x-text="Number(product.active_price).toFixed(0) + ' ر.س'"></span>
                                    <template x-if="product.discount_price">
                                        <span class="block text-xs text-gray-400 line-through mt-0.5"
                                              x-text="Number(product.price).toFixed(0) + ' ر.س'"></span>
                                    </template>
                                </div>

                                {{-- Buttons --}}
                                <div class="flex gap-2">
                                    <a :href="product.product_url"
                                       class="flex-1 text-white font-extrabold py-3 rounded-xl text-sm text-center transition-all duration-200 flex items-center justify-center gap-2 hover:opacity-90"
                                       style="background:#1b3d2e;"
                                       :class="!product.in_stock ? 'opacity-60 pointer-events-none' : ''">
                                        <i class="fa-solid fa-cart-shopping text-xs"></i>
                                        <span x-text="product.in_stock ? 'اختر' : 'نفذ'"></span>
                                    </a>
                                    <button type="button"
                                            @click="remove(product.id)"
                                            class="w-11 h-11 rounded-xl border border-red-200 text-red-400 hover:bg-red-50 hover:text-red-600 transition-all duration-200 flex items-center justify-center flex-shrink-0"
                                            title="إزالة من المفضلة">
                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Bottom action --}}
                <div class="flex justify-center mt-10">
                    <a href="{{ route('products.index') }}"
                       class="inline-flex items-center gap-2 font-bold text-sm text-gray-500 hover:text-gray-700 transition-colors">
                        <i class="fa-solid fa-arrow-right-long text-xs"></i>
                        متابعة التسوق
                    </a>
                </div>
            </div>
        </template>

    </div>
</div>
@endsection
