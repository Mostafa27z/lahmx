@extends('layouts.admin')

@section('title', 'إدارة الطلبات - لوحة التحكم')

@section('content')
<div class="space-y-6">

    <div>
        <h1 class="text-3xl font-bold text-gray-900">إدارة الطلبات 📦</h1>
        <p class="text-gray-500 mt-1">تتبع وإدارة طلبات العملاء وتحديث حالتها</p>
    </div>

    {{-- Status Filter Tabs --}}
    <div class="flex flex-wrap gap-2">
        @php
            $statuses = [
                '' => ['label' => 'الكل', 'icon' => '📋'],
                'pending'    => ['label' => 'قيد الانتظار',  'icon' => '⏳'],
                'confirmed'  => ['label' => 'مؤكد',          'icon' => '✅'],
                'processing' => ['label' => 'قيد التجهيز',  'icon' => '⚙️'],
                'shipped'    => ['label' => 'تم الشحن',      'icon' => '🚚'],
                'delivered'  => ['label' => 'تم التوصيل',   'icon' => '🏠'],
                'cancelled'  => ['label' => 'ملغي',          'icon' => '❌'],
            ];
        @endphp
        @foreach($statuses as $key => $info)
            <a href="{{ route('admin.orders.index', $key ? ['status' => $key] : []) }}"
               class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold transition
                      {{ $status === $key ? 'bg-primary text-white shadow' : 'bg-white border border-gray-100 text-gray-600 hover:bg-gray-50' }}">
                <span>{{ $info['icon'] }}</span>
                {{ $info['label'] }}
            </a>
        @endforeach
    </div>

    {{-- Orders Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-right border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 font-bold border-b border-gray-100 text-xs">
                    <th class="p-5">رقم الطلب</th>
                    <th class="p-5">العميل</th>
                    <th class="p-5">المدينة</th>
                    <th class="p-5">الإجمالي</th>
                    <th class="p-5">طريقة الدفع</th>
                    <th class="p-5 text-center">حالة الطلب</th>
                    <th class="p-5 text-center">حالة الدفع</th>
                    <th class="p-5">التاريخ</th>
                    <th class="p-5 text-center">التفاصيل</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-gray-700">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="p-5">
                            <a href="{{ route('admin.orders.show', $order->id) }}"
                               class="font-extrabold text-primary hover:underline text-sm">
                                {{ $order->order_number }}
                            </a>
                        </td>
                        <td class="p-5">
                            <p class="font-bold text-gray-800 text-sm">{{ $order->customer_name }}</p>
                            <p class="text-xs text-gray-400 font-semibold">{{ $order->phone }}</p>
                        </td>
                        <td class="p-5 text-sm text-gray-500 font-semibold">{{ $order->city ?? '—' }}</td>
                        <td class="p-5 font-extrabold text-primary text-sm">{{ number_format($order->total, 2) }} ر.س</td>
                        <td class="p-5 text-xs text-gray-500 font-semibold">{{ $order->payment_method_label }}</td>
                        <td class="p-5 text-center">
                            @php
                                $sc = [
                                    'pending'    => 'bg-yellow-100 text-yellow-800',
                                    'confirmed'  => 'bg-blue-100 text-blue-800',
                                    'processing' => 'bg-purple-100 text-purple-800',
                                    'shipped'    => 'bg-indigo-100 text-indigo-800',
                                    'delivered'  => 'bg-green-100 text-green-800',
                                    'cancelled'  => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold {{ $sc[$order->status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="p-5 text-center">
                            <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold
                                {{ $order->payment_status === 'paid'   ? 'bg-green-100 text-green-800'  :
                                  ($order->payment_status === 'failed' ? 'bg-red-100 text-red-800'     : 'bg-gray-100 text-gray-600') }}">
                                {{ $order->payment_status_label }}
                            </span>
                        </td>
                        <td class="p-5 text-xs text-gray-400 font-semibold">{{ $order->created_at->format('d/m/Y') }}</td>
                        <td class="p-5 text-center">
                            <a href="{{ route('admin.orders.show', $order->id) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold transition">
                                <i class="fa-solid fa-eye text-[10px]"></i> عرض
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="p-14 text-center text-gray-400 font-bold">
                            <div class="flex flex-col items-center gap-3">
                                <span class="text-5xl">📦</span>
                                <span>لا توجد طلبات بهذا الفلتر حالياً.</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-5 border-t border-gray-50 flex items-center justify-between">
            <p class="text-xs text-gray-400 font-semibold">إجمالي النتائج: {{ $orders->total() }} طلب</p>
            {{ $orders->links() }}
        </div>
    </div>

</div>
@endsection
