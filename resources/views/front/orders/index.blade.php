@extends('layouts.app')

@section('title', 'طلباتي - لحمكس')

@section('content')
<div class="py-12 bg-bg-custom min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-extrabold text-primary mb-12">قائمة طلباتي 📦</h1>

        @if($orders->isEmpty())
            <div class="bg-white rounded-3xl p-16 text-center border border-red-50 shadow-sm">
                <span class="text-7xl block mb-6">📦</span>
                <h2 class="text-2xl font-extrabold text-text-custom mb-4">لا توجد طلبات سابقة!</h2>
                <p class="text-gray-400 mb-8">لم تقم بإجراء أي طلبات حتى الآن.</p>
                <a href="{{ route('products.index') }}" class="bg-primary hover:bg-primary-dark text-white font-extrabold px-8 py-4 rounded-xl transition inline-block">تسوق الآن 🥩</a>
            </div>
        @else
            <div class="bg-white rounded-3xl border border-red-50 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-right border-collapse">
                        <thead>
                            <tr class="bg-red-50/55 border-b border-red-100 text-primary font-bold">
                                <th class="p-6">رقم الطلب</th>
                                <th class="p-6">التاريخ</th>
                                <th class="p-6">الحالة</th>
                                <th class="p-6">طريقة الدفع</th>
                                <th class="p-6">الإجمالي</th>
                                <th class="p-6">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-red-50 font-semibold text-text-custom">
                            @foreach($orders as $order)
                                <tr class="hover:bg-red-50/20 transition">
                                    <td class="p-6 text-primary font-bold">{{ $order->order_number }}</td>
                                    <td class="p-6 text-sm text-gray-500">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="p-6">
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold 
                                            {{ $order->status == 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $order->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                            {{ !in_array($order->status, ['delivered', 'pending', 'cancelled']) ? 'bg-blue-100 text-blue-800' : '' }}
                                        ">
                                            {{ $order->status_label }}
                                        </span>
                                    </td>
                                    <td class="p-6 text-sm">{{ $order->payment_method_label }}</td>
                                    <td class="p-6 font-bold text-primary">{{ $order->total }} ر.س</td>
                                    <td class="p-6">
                                        <a href="{{ route('orders.show', $order->id) }}" class="text-secondary hover:text-primary transition underline">التفاصيل &larr;</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
