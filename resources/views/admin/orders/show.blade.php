@extends('layouts.admin')

@section('title', 'تفاصيل الطلب #{{ $order->order_number }} - لوحة التحكم')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">

    {{-- Back + Title --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.orders.index') }}" class="text-gray-500 hover:text-gray-700 font-bold text-sm flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-right"></i> العودة للطلبات
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-900">طلب رقم #{{ $order->order_number }}</h1>
            <p class="text-gray-400 text-xs font-semibold mt-0.5">{{ $order->created_at->format('d/m/Y — h:i A') }}</p>
        </div>
    </div>

    {{-- Status Update Form --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="text-base font-extrabold text-gray-800 mb-4">🔄 تحديث حالة الطلب</h2>
        <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="flex flex-wrap gap-4 items-end">
            @csrf
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-bold text-gray-500 mb-1.5">حالة الطلب</label>
                <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary text-right font-semibold text-sm">
                    @foreach(['pending'=>'قيد الانتظار','confirmed'=>'تم التأكيد','processing'=>'قيد التجهيز','shipped'=>'تم الشحن','delivered'=>'تم التوصيل','cancelled'=>'ملغي'] as $val => $label)
                        <option value="{{ $val }}" {{ $order->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-bold text-gray-500 mb-1.5">حالة الدفع</label>
                <select name="payment_status" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary text-right font-semibold text-sm">
                    @foreach(['pending'=>'لم يدفع','paid'=>'مدفوع','failed'=>'فشلت عملية الدفع'] as $val => $label)
                        <option value="{{ $val }}" {{ $order->payment_status === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-extrabold px-6 py-2.5 rounded-xl transition shadow text-sm cursor-pointer">
                حفظ التغييرات
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Order Items --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-50">
                <h2 class="text-base font-extrabold text-gray-800">🛒 المنتجات المطلوبة</h2>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($order->items as $item)
                    <div class="p-5 flex items-center gap-4">
                        {{-- Product image --}}
                        <div class="w-14 h-14 rounded-xl bg-gray-50 border border-gray-100 overflow-hidden flex-shrink-0 flex items-center justify-center text-xl">
                            @if($item->product && $item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="" class="w-full h-full object-cover">
                            @else
                                🥩
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-gray-800 text-sm">{{ $item->product->name ?? 'منتج محذوف' }}</p>
                            @if($item->options)
                                <span class="inline-block bg-red-50 text-secondary text-[10px] font-bold px-2 py-0.5 rounded-md mt-1">{{ $item->options }}</span>
                            @endif
                            <p class="text-xs text-gray-400 font-semibold mt-1">{{ number_format($item->price, 2) }} ر.س × {{ $item->quantity }}</p>
                        </div>
                        <p class="font-extrabold text-primary text-sm">{{ number_format($item->price * $item->quantity, 2) }} ر.س</p>
                    </div>
                @endforeach
            </div>
            {{-- Totals --}}
            <div class="border-t border-gray-100 p-5 space-y-2">
                <div class="flex justify-between text-sm text-gray-500 font-semibold">
                    <span>المجموع الفرعي</span>
                    <span>{{ number_format($order->subtotal, 2) }} ر.س</span>
                </div>
                <div class="flex justify-between text-sm text-gray-500 font-semibold">
                    <span>رسوم التوصيل</span>
                    <span>{{ number_format($order->delivery_fee, 2) }} ر.س</span>
                </div>
                <div class="flex justify-between text-base font-extrabold text-gray-800 pt-2 border-t border-gray-100">
                    <span>الإجمالي</span>
                    <span class="text-primary">{{ number_format($order->total, 2) }} ر.س</span>
                </div>
            </div>
        </div>

        {{-- Sidebar: Customer + Payment Info --}}
        <div class="space-y-5">

            {{-- Customer Info --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h3 class="text-sm font-extrabold text-gray-800 mb-4">👤 بيانات العميل</h3>
                <div class="space-y-2.5 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400 font-semibold">الاسم</span>
                        <span class="font-bold text-gray-800">{{ $order->customer_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400 font-semibold">الهاتف</span>
                        <span class="font-bold text-gray-800" dir="ltr">{{ $order->phone }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400 font-semibold">المدينة</span>
                        <span class="font-bold text-gray-800">{{ $order->city ?? '—' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 font-semibold block mb-1">العنوان</span>
                        <p class="font-semibold text-gray-700 text-xs leading-relaxed bg-gray-50 rounded-xl p-3">{{ $order->address ?? '—' }}</p>
                    </div>
                    @if($order->notes)
                        <div>
                            <span class="text-gray-400 font-semibold block mb-1">ملاحظات</span>
                            <p class="font-semibold text-gray-700 text-xs leading-relaxed bg-amber-50 rounded-xl p-3 border border-amber-100">{{ $order->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Payment Info --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h3 class="text-sm font-extrabold text-gray-800 mb-4">💳 معلومات الدفع</h3>
                <div class="space-y-2.5 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400 font-semibold">طريقة الدفع</span>
                        <span class="font-bold text-gray-800">{{ $order->payment_method_label }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 font-semibold">حالة الدفع</span>
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold
                            {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' :
                               ($order->payment_status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-600') }}">
                            {{ $order->payment_status_label }}
                        </span>
                    </div>
                    @if($order->payment)
                        <div class="flex justify-between">
                            <span class="text-gray-400 font-semibold">رقم المعاملة</span>
                            <span class="font-bold text-gray-800 text-xs" dir="ltr">{{ $order->payment->transaction_id ?? '—' }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Order Status Badge --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 text-center">
                @php
                    $statusColors = [
                        'pending'    => 'bg-yellow-100 text-yellow-800',
                        'confirmed'  => 'bg-blue-100 text-blue-800',
                        'processing' => 'bg-purple-100 text-purple-800',
                        'shipped'    => 'bg-indigo-100 text-indigo-800',
                        'delivered'  => 'bg-green-100 text-green-800',
                        'cancelled'  => 'bg-red-100 text-red-800',
                    ];
                @endphp
                <p class="text-xs text-gray-400 font-bold mb-2">حالة الطلب الحالية</p>
                <span class="inline-block px-5 py-2 rounded-full text-sm font-extrabold {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-600' }}">
                    {{ $order->status_label }}
                </span>
            </div>

        </div>
    </div>

</div>
@endsection
