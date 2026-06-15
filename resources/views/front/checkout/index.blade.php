@extends('layouts.app')

@section('title', 'إتمام الطلب - لحمكس')

@section('content')
<div class="py-12 bg-bg-custom min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-extrabold text-primary mb-12">إتمام الشراء والدفع 💳</h1>

        <form action="{{ route('checkout.process') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf

            <!-- Customer Details Form -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-3xl p-8 border border-red-50 shadow-sm space-y-6">
                    <h3 class="text-xl font-bold text-primary border-b border-red-50 pb-4 mb-4">معلومات التوصيل</h3>

                    <!-- Customer Name -->
                    <div>
                        <label for="customer_name" class="block text-sm font-bold text-text-custom mb-2">اسم المستلم</label>
                        <input type="text" name="customer_name" id="customer_name" required 
                               value="{{ old('customer_name', auth()->user()?->name) }}"
                               class="w-full px-4 py-3 rounded-lg border border-red-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-right">
                        @error('customer_name')
                            <span class="text-red-600 text-xs font-semibold mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-bold text-text-custom mb-2">رقم الجوال</label>
                        <input type="text" name="phone" id="phone" required placeholder="05xxxxxxxx"
                               value="{{ old('phone', auth()->user()?->phone) }}"
                               class="w-full px-4 py-3 rounded-lg border border-red-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-right">
                        @error('phone')
                            <span class="text-red-600 text-xs font-semibold mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- City and Address -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-1">
                            <label for="city" class="block text-sm font-bold text-text-custom mb-2">المدينة</label>
                            <select name="city" id="city" required
                                    class="w-full px-4 py-3 rounded-lg border border-red-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-right">
                                <option value="الرياض" {{ old('city') == 'الرياض' ? 'selected' : '' }}>الرياض</option>
                                <option value="جدة" {{ old('city') == 'جدة' ? 'selected' : '' }}>جدة</option>
                                <option value="الدمام" {{ old('city') == 'الدمام' ? 'selected' : '' }}>الدمام</option>
                                <option value="مكة المكرمة" {{ old('city') == 'مكة المكرمة' ? 'selected' : '' }}>مكة المكرمة</option>
                                <option value="المدينة المنورة" {{ old('city') == 'المدينة المنورة' ? 'selected' : '' }}>المدينة المنورة</option>
                            </select>
                            @error('city')
                                <span class="text-red-600 text-xs font-semibold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-bold text-text-custom mb-2">العنوان بالتفصيل</label>
                            <input type="text" name="address" id="address" required placeholder="الحي، اسم الشارع، رقم المنزل"
                                   value="{{ old('address') }}"
                                   class="w-full px-4 py-3 rounded-lg border border-red-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-right">
                            @error('address')
                                <span class="text-red-600 text-xs font-semibold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-bold text-text-custom mb-2">ملاحظات إضافية للطلب (اختياري)</label>
                        <textarea name="notes" id="notes" rows="3" placeholder="ملاحظات حول التوصيل أو التقطيع والتغليف..."
                                  class="w-full px-4 py-3 rounded-lg border border-red-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-right">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Payment Methods Card -->
                <div class="bg-white rounded-3xl p-8 border border-red-50 shadow-sm space-y-6">
                    <h3 class="text-xl font-bold text-primary border-b border-red-50 pb-4 mb-4">طريقة الدفع</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6" x-data="{ method: 'visa' }">
                        <!-- Visa Option -->
                        <label class="relative flex flex-col items-center justify-center p-6 border-2 rounded-2xl cursor-pointer transition focus:outline-none"
                               :class="method === 'visa' ? 'border-primary bg-red-50/20' : 'border-red-100 hover:bg-red-50/10'"
                               @click="method = 'visa'">
                            <input type="radio" name="payment_method" value="visa" class="sr-only" checked>
                            <span class="text-4xl mb-3">💳</span>
                            <span class="font-extrabold text-text-custom">بطاقة فيزا الائتمانية</span>
                        </label>

                        <!-- Tabby Option -->
                        <label class="relative flex flex-col items-center justify-center p-6 border-2 rounded-2xl cursor-pointer transition focus:outline-none"
                               :class="method === 'tabby' ? 'border-primary bg-red-50/20' : 'border-red-100 hover:bg-red-50/10'"
                               @click="method = 'tabby'">
                            <input type="radio" name="payment_method" value="tabby" class="sr-only">
                            <span class="text-4xl mb-3">🏦</span>
                            <span class="font-extrabold text-text-custom">تقسيط تابي (Tabby)</span>
                        </label>

                        <!-- Tamara Option -->
                        <label class="relative flex flex-col items-center justify-center p-6 border-2 rounded-2xl cursor-pointer transition focus:outline-none"
                               :class="method === 'tamara' ? 'border-primary bg-red-50/20' : 'border-red-100 hover:bg-red-50/10'"
                               @click="method = 'tamara'">
                            <input type="radio" name="payment_method" value="tamara" class="sr-only">
                            <span class="text-4xl mb-3">📱</span>
                            <span class="font-extrabold text-text-custom">تقسيط تمارا (Tamara)</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Order Summary Card -->
            <div class="bg-white rounded-3xl p-8 border border-red-50 shadow-sm h-fit">
                <h3 class="text-xl font-bold text-primary mb-6 border-b border-red-50 pb-4">محتويات السلة</h3>

                <div class="space-y-4 max-h-60 overflow-y-auto mb-6 border-b border-red-50 pb-6">
                    @foreach($cart->items as $item)
                        <div class="flex justify-between items-center text-sm">
                            <div class="font-bold text-text-custom">
                                {{ $item->product->name }} <span class="text-gray-400 font-semibold">x{{ $item->quantity }}</span>
                            </div>
                            <div class="font-extrabold text-primary">
                                {{ $item->subtotal }} ر.س
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="space-y-4 mb-8">
                    <div class="flex justify-between font-semibold text-sm">
                        <span class="text-gray-500">المجموع الفرعي:</span>
                        <span class="text-text-custom">{{ $cart->total }} ر.س</span>
                    </div>
                    <div class="flex justify-between font-semibold text-sm">
                        <span class="text-gray-500">التوصيل:</span>
                        <span class="text-text-custom">15.00 ر.س</span>
                    </div>
                    <div class="border-t border-red-50 pt-4 flex justify-between font-extrabold text-lg">
                        <span class="text-primary">الإجمالي النهائي:</span>
                        <span class="text-primary">{{ $cart->total + 15 }} ر.س</span>
                    </div>
                </div>

                <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-extrabold py-4 rounded-xl transition shadow-lg text-center">
                    تأكيد الطلب والدفع 🛡️
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
