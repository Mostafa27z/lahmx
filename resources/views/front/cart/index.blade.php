@extends('layouts.app')

@section('title', 'سلة المشتريات - لحمكس')

@section('content')
<div class="py-12 bg-bg-custom min-h-screen" x-data="{ 
    totalPrice: {{ $cart->total }}, 
    updateItem(itemId, qty) {
        if (qty < 1) {
            this.removeItem(itemId);
            return;
        }
        
        fetch(`/cart/update/${itemId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ quantity: qty })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                this.totalPrice = data.total;
                document.getElementById(`subtotal-${itemId}`).innerText = data.item_subtotal.toFixed(2) + ' ر.س';
                
                const AlpineBody = document.querySelector('body');
                if (AlpineBody && AlpineBody.__x) {
                    AlpineBody.__x.$data.cartCount = data.items_count;
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: data.message,
                    confirmButtonColor: '#7A0C0C'
                });
            }
        });
    },
    removeItem(itemId) {
        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: 'هل تريد حذف هذا المنتج من السلة؟',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#7A0C0C',
            cancelButtonColor: '#B22222',
            confirmButtonText: 'نعم، احذف',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/cart/remove/${itemId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        this.totalPrice = data.total;
                        document.getElementById(`row-${itemId}`).remove();
                        
                        const AlpineBody = document.querySelector('body');
                        if (AlpineBody && AlpineBody.__x) {
                            AlpineBody.__x.$data.cartCount = data.items_count;
                        }

                        if (data.items_count === 0) {
                            window.location.reload();
                        }
                    }
                });
            }
        });
    }
}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-extrabold text-primary mb-12">سلة المشتريات 🛒</h1>

        @if($cart->items->isEmpty())
            <div class="bg-white rounded-3xl p-16 text-center border border-red-50 shadow-sm">
                <span class="text-7xl block mb-6">🛒</span>
                <h2 class="text-2xl font-extrabold text-text-custom mb-4">سلة المشتريات فارغة حالياً!</h2>
                <p class="text-gray-400 mb-8 max-w-md mx-auto">تصفح متجرنا الفاخر الآن واختر أفضل قطع اللحم الطازجة لتصلك فوراً.</p>
                <a href="{{ route('products.index') }}" class="bg-primary hover:bg-primary-dark text-white font-extrabold px-8 py-4 rounded-xl transition duration-150 inline-block shadow-lg">تصفح المتجر 🥩</a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items List -->
                <div class="lg:col-span-2 space-y-6">
                    @foreach($cart->items as $item)
                        <div id="row-{{ $item->id }}" class="bg-white rounded-2xl p-6 border border-red-50 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-6">
                            <!-- Product Info -->
                            <div class="flex items-center gap-4 w-full sm:w-auto">
                                <div class="w-20 h-20 bg-red-50 rounded-xl overflow-hidden flex items-center justify-center text-3xl border border-red-50 flex-shrink-0">
                                    @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                    @else
                                        🥩
                                    @endif
                                </div>
                                <div>
                                    <h3 class="font-bold text-text-custom text-lg mb-1">{{ $item->product->name }}</h3>
                                    @if($item->options)
                                        <span class="bg-red-50 text-secondary text-xs px-2.5 py-1 rounded-md block mb-1.5 w-fit font-bold">{{ $item->options }}</span>
                                    @endif
                                    <span class="text-gray-400 text-sm">سعر القطعة: {{ $item->price }} ر.س</span>
                                </div>
                            </div>

                            <!-- Controls & Subtotal -->
                            <div class="flex items-center justify-between w-full sm:w-auto gap-8" x-data="{ qty: {{ $item->quantity }} }">
                                <!-- Quantity controls -->
                                <div class="flex items-center border border-red-100 rounded-lg overflow-hidden bg-white">
                                    <button @click="qty++; updateItem({{ $item->id }}, qty)" class="px-3 py-1 bg-red-50 hover:bg-red-100 font-bold">+</button>
                                    <span class="px-4 py-1 font-bold" x-text="qty"></span>
                                    <button @click="if (qty > 1) { qty--; updateItem({{ $item->id }}, qty) }" class="px-3 py-1 bg-red-50 hover:bg-red-100 font-bold">-</button>
                                </div>

                                <!-- Subtotal -->
                                <div class="text-left font-extrabold text-primary min-w-[100px]" id="subtotal-{{ $item->id }}">
                                    {{ $item->subtotal }} ر.س
                                </div>

                                <!-- Remove -->
                                <button @click="removeItem({{ $item->id }})" class="text-red-500 hover:text-red-700 transition" title="حذف">
                                    🗑️
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Checkout Summary Card -->
                <div class="bg-white rounded-3xl p-8 border border-red-50 shadow-sm h-fit">
                    <h3 class="text-xl font-bold text-primary mb-6 border-b border-red-50 pb-4">ملخص الطلب</h3>
                    
                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between font-semibold">
                            <span class="text-gray-500">المجموع الفرعي:</span>
                            <span class="text-text-custom" x-text="totalPrice.toFixed(2) + ' ر.س'"></span>
                        </div>
                        <div class="flex justify-between font-semibold">
                            <span class="text-gray-500">رسوم التوصيل:</span>
                            <span class="text-text-custom">15.00 ر.س</span>
                        </div>
                        <div class="border-t border-red-50 pt-4 flex justify-between font-extrabold text-lg">
                            <span class="text-primary">الإجمالي الكلي:</span>
                            <span class="text-primary" x-text="(totalPrice + 15).toFixed(2) + ' ر.س'"></span>
                        </div>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="w-full bg-primary hover:bg-primary-dark text-white font-extrabold py-4 rounded-xl transition shadow-lg text-center block">
                        الانتقال للدفع 💳
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
