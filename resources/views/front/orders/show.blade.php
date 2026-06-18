@extends('layouts.app')

@section('title', 'تفاصيل الطلب ' . $order->order_number . ' - لحمكس')

@section('content')
<div class="py-12 bg-bg-custom min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-extrabold text-primary">تفاصيل طلبك 📦</h1>
            @auth
                <a href="{{ route('orders.index') }}" class="text-sm font-bold text-secondary hover:underline">&larr; العودة لقائمة طلباتي</a>
            @endauth
        </div>

        <div class="space-y-8">
            
            <!-- Order status banner -->
            <div class="bg-white rounded-3xl p-8 border border-red-50 shadow-sm flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <span class="text-xs font-bold text-gray-400 block mb-1">رقم الطلب</span>
                    <span class="text-2xl font-extrabold text-primary">{{ $order->order_number }}</span>
                    <span class="text-xs text-gray-400 block mt-1">تاريخ الطلب: {{ $order->created_at->format('Y-m-d H:i') }}</span>
                </div>
                <div class="flex flex-wrap gap-4">
                    <div class="text-center bg-red-50/50 border border-red-100 rounded-xl px-5 py-3">
                        <span class="text-xs text-gray-500 font-bold block mb-1">حالة الطلب</span>
                        <span class="font-extrabold text-primary">{{ $order->status_label }}</span>
                    </div>
                    <div class="text-center bg-red-50/50 border border-red-100 rounded-xl px-5 py-3">
                        <span class="text-xs text-gray-500 font-bold block mb-1">حالة الدفع</span>
                        <span class="font-extrabold text-primary">{{ $order->payment_status_label }}</span>
                    </div>
                </div>
            </div>

            <!-- Customer Details & Shipping info -->
            <div class="bg-white rounded-3xl p-8 border border-red-50 shadow-sm grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-bold text-primary mb-4 border-r-4 border-primary pr-3">بيانات المستلم</h3>
                    <ul class="space-y-3 font-semibold text-text-custom text-sm">
                        <li><span class="text-gray-400">الاسم:</span> {{ $order->customer_name }}</li>
                        <li><span class="text-gray-400">الجوال:</span> {{ $order->phone }}</li>
                        <li><span class="text-gray-400">المدينة:</span> {{ $order->city }}</li>
                        <li><span class="text-gray-400">العنوان بالتفصيل:</span> {{ $order->address }}</li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-primary mb-4 border-r-4 border-primary pr-3">بيانات إضافية</h3>
                    <ul class="space-y-3 font-semibold text-text-custom text-sm">
                        <li><span class="text-gray-400">طريقة الدفع:</span> {{ $order->payment_method_label }}</li>
                        @if($order->payment && $order->payment->transaction_id)
                            <li><span class="text-gray-400">رقم المعاملة:</span> {{ $order->payment->transaction_id }}</li>
                        @endif
                        <li><span class="text-gray-400">ملاحظات العميل:</span> {{ $order->notes ?? 'لا توجد ملاحظات.' }}</li>
                    </ul>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-3xl border border-red-50 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-red-50">
                    <h3 class="text-lg font-bold text-primary">المنتجات المطلوبة</h3>
                </div>
                <table class="w-full text-right border-collapse">
                    <thead>
                        <tr class="bg-red-50/20 text-text-custom font-bold border-b border-red-50">
                            <th class="p-4">المنتج</th>
                            <th class="p-4 text-center">الكمية</th>
                            <th class="p-4">سعر الوحدة</th>
                            <th class="p-4 text-left">الإجمالي</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-red-50 font-semibold text-text-custom">
                        @foreach($order->items as $item)
                            <tr>
                                <td class="p-4 flex items-center gap-3">
                                    <span class="text-2xl">🥩</span>
                                    <div>
                                        <div class="font-bold">{{ $item->product ? $item->product->name : 'منتج غير متوفر' }}</div>
                                        @if($item->options)
                                            <span class="text-xs text-secondary bg-red-50 px-2 py-0.5 rounded-md font-bold mt-1 inline-block">{{ $item->options }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-4 text-center">{{ $item->quantity }}</td>
                                <td class="p-4">{{ $item->price }} ر.س</td>
                                <td class="p-4 text-left font-bold text-primary">{{ $item->total }} ر.س</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="bg-red-50/30 p-6 border-t border-red-50 space-y-3 text-sm font-semibold">
                    <div class="flex justify-between">
                        <span class="text-gray-500">المجموع الفرعي:</span>
                        <span class="text-text-custom">{{ $order->subtotal }} ر.س</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">رسوم التوصيل المبرد:</span>
                        <span class="text-text-custom">{{ $order->delivery_fee }} ر.س</span>
                    </div>
                    <div class="flex justify-between font-extrabold text-lg border-t border-red-100 pt-3">
                        <span class="text-primary">الإجمالي النهائي:</span>
                        <span class="text-primary">{{ $order->total }} ر.س</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
