@extends('layouts.admin')

@section('title', 'العمليات المالية - لوحة التحكم')

@section('content')
<div class="space-y-6">

    <div>
        <h1 class="text-3xl font-bold text-gray-900">العمليات المالية 💳</h1>
        <p class="text-gray-500 mt-1">سجل كافة عمليات الدفع في المتجر</p>
    </div>

    {{-- Method Filter Tabs --}}
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.payments.index') }}"
           class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold transition
                  {{ !$method ? 'bg-primary text-white shadow' : 'bg-white border border-gray-100 text-gray-600 hover:bg-gray-50' }}">
            📋 الكل
        </a>
        @foreach(['visa' => 'بطاقة ائتمانية (Visa)', 'tabby' => 'تابي (Tabby)', 'tamara' => 'تمارا (Tamara)'] as $val => $label)
            <a href="{{ route('admin.payments.index', ['method' => $val]) }}"
               class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold transition
                      {{ $method === $val ? 'bg-primary text-white shadow' : 'bg-white border border-gray-100 text-gray-600 hover:bg-gray-50' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Payments Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-right border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 font-bold border-b border-gray-100 text-xs">
                    <th class="p-5">#</th>
                    <th class="p-5">رقم الطلب</th>
                    <th class="p-5">العميل</th>
                    <th class="p-5">طريقة الدفع</th>
                    <th class="p-5">رقم المعاملة</th>
                    <th class="p-5">المبلغ</th>
                    <th class="p-5 text-center">الحالة</th>
                    <th class="p-5">التاريخ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-gray-700">
                @forelse($payments as $payment)
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="p-5 text-xs text-gray-400 font-bold">{{ $payment->id }}</td>
                        <td class="p-5">
                            @if($payment->order)
                                <a href="{{ route('admin.orders.show', $payment->order->id) }}"
                                   class="font-extrabold text-primary hover:underline text-sm">
                                    {{ $payment->order->order_number }}
                                </a>
                            @else
                                <span class="text-gray-300 font-bold text-sm">—</span>
                            @endif
                        </td>
                        <td class="p-5">
                            <p class="font-bold text-gray-800 text-sm">{{ $payment->order->customer_name ?? '—' }}</p>
                            <p class="text-xs text-gray-400 font-semibold">{{ $payment->order->phone ?? '' }}</p>
                        </td>
                        <td class="p-5 text-sm font-semibold text-gray-600">{{ $payment->method_label }}</td>
                        <td class="p-5 text-xs text-gray-400 font-semibold" dir="ltr">
                            {{ $payment->transaction_id ?? '—' }}
                        </td>
                        <td class="p-5 font-extrabold text-primary text-sm">{{ number_format($payment->amount, 2) }} ر.س</td>
                        <td class="p-5 text-center">
                            <span class="inline-block px-2.5 py-1 rounded-full text-xs font-bold
                                {{ $payment->status === 'success' ? 'bg-green-100 text-green-800' :
                                   ($payment->status === 'failed'  ? 'bg-red-100 text-red-800'   : 'bg-yellow-100 text-yellow-800') }}">
                                {{ $payment->status_label }}
                            </span>
                        </td>
                        <td class="p-5 text-xs text-gray-400 font-semibold">{{ $payment->created_at->format('d/m/Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="p-14 text-center text-gray-400 font-bold">
                            <div class="flex flex-col items-center gap-3">
                                <span class="text-5xl">💳</span>
                                <span>لا توجد عمليات دفع حالياً.</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-5 border-t border-gray-50 flex items-center justify-between">
            <p class="text-xs text-gray-400 font-semibold">
                إجمالي النتائج: {{ $payments->total() }} عملية
            </p>
            {{ $payments->links() }}
        </div>
    </div>

</div>
@endsection
