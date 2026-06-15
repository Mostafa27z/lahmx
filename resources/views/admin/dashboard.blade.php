@extends('layouts.admin')

@section('title', 'لوحة التحكم - الإحصائيات')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-gray-900">نظرة عامة على المتجر 📊</h1>
        <p class="text-gray-500 mt-1">متابعة إحصائيات متجر لحمكس الفوريّة</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Sales -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-sm font-semibold text-gray-400 block mb-1">إجمالي المبيعات</span>
                <span class="text-3xl font-extrabold text-primary">{{ number_format($totalSales, 2) }} ر.س</span>
            </div>
            <span class="text-4xl bg-red-50 p-3 rounded-2xl">💰</span>
        </div>

        <!-- Orders -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-sm font-semibold text-gray-400 block mb-1">عدد الطلبات</span>
                <span class="text-3xl font-extrabold text-gray-800">{{ $ordersCount }}</span>
            </div>
            <span class="text-4xl bg-red-50 p-3 rounded-2xl">📦</span>
        </div>

        <!-- Products -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-sm font-semibold text-gray-400 block mb-1">المنتجات</span>
                <span class="text-3xl font-extrabold text-gray-800">{{ $productsCount }}</span>
            </div>
            <span class="text-4xl bg-red-50 p-3 rounded-2xl">🥩</span>
        </div>

        <!-- Categories -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-sm font-semibold text-gray-400 block mb-1">الأقسام</span>
                <span class="text-3xl font-extrabold text-gray-800">{{ $categoriesCount }}</span>
            </div>
            <span class="text-4xl bg-red-50 p-3 rounded-2xl">📁</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Orders -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col justify-between">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">أحدث الطلبات 📦</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-sm font-semibold text-primary hover:underline">عرض الكل &larr;</a>
            </div>
            @if($recentOrders->isEmpty())
                <div class="p-12 text-center text-gray-400 font-semibold">
                    لا توجد طلبات حالية.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-right border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 font-bold border-b border-gray-100">
                                <th class="p-4">رقم الطلب</th>
                                <th class="p-4">العميل</th>
                                <th class="p-4">الإجمالي</th>
                                <th class="p-4">الحالة</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 font-semibold text-gray-700">
                            @foreach($recentOrders as $order)
                                <tr class="hover:bg-gray-50/50">
                                    <td class="p-4"><a href="{{ route('admin.orders.show', $order->id) }}" class="text-primary hover:underline font-bold">{{ $order->order_number }}</a></td>
                                    <td class="p-4">{{ $order->customer_name }}</td>
                                    <td class="p-4 font-bold text-primary">{{ $order->total }} ر.س</td>
                                    <td class="p-4">
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold 
                                            {{ $order->status == 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $order->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                            {{ !in_array($order->status, ['delivered', 'pending', 'cancelled']) ? 'bg-blue-100 text-blue-800' : '' }}
                                        ">
                                            {{ $order->status_label }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Low Stock Alerts -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
            <div class="p-6 border-b border-gray-50">
                <h3 class="text-lg font-bold text-gray-800">تنبيهات المخزون ⚠️</h3>
            </div>
            @if($lowStockProducts->isEmpty())
                <div class="p-12 text-center text-green-500 font-bold">
                    ✅ جميع المنتجات متوفرة بمخزون كافٍ.
                </div>
            @else
                <div class="divide-y divide-gray-50 overflow-y-auto max-h-[350px]">
                    @foreach($lowStockProducts as $prod)
                        <div class="p-4 flex items-center justify-between">
                            <div>
                                <span class="font-bold text-gray-800 block">{{ $prod->name }}</span>
                                <span class="text-xs text-gray-400">{{ $prod->category->name }}</span>
                            </div>
                            <span class="px-3 py-1 rounded-lg text-xs font-extrabold bg-red-100 text-red-800">
                                المتبقي: {{ $prod->stock_quantity }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
