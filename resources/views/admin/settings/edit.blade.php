@extends('layouts.admin')

@section('title', 'إعدادات معلومات الشركة - لحمكس')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">⚙️ إعدادات معلومات الشركة</h1>
        <p class="text-gray-500 mt-1">تعديل معلومات الاتصال، شبكات التواصل الاجتماعي، والأرقام الإحصائية للموقع.</p>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Card: Basic Company Info -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h2 class="text-lg font-bold text-gray-700 mb-4 pb-2 border-b border-gray-50">🏢 معلومات الشركة الأساسية</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-2">اسم الشركة</label>
                    <input type="text" name="company_name" value="{{ $settings['company_name'] }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-2">البريد الإلكتروني للشركة</label>
                    <input type="email" name="company_email" value="{{ $settings['company_email'] }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition text-left" dir="ltr">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-2">رقم الهاتف</label>
                    <input type="text" name="company_phone" value="{{ $settings['company_phone'] }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition text-left" dir="ltr">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-2">رقم الواتساب للطلب والاستفسار</label>
                    <input type="text" name="company_whatsapp" value="{{ $settings['company_whatsapp'] }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition text-left" dir="ltr">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-600 mb-2">عنوان المقر أو الفرع الرئيسي</label>
                    <input type="text" name="company_address" value="{{ $settings['company_address'] }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition">
                </div>
            </div>
        </div>

        <!-- Card: Social Links -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h2 class="text-lg font-bold text-gray-700 mb-4 pb-2 border-b border-gray-50">📱 شبكات التواصل الاجتماعي</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-2">سناب شات (Snapchat Link)</label>
                    <input type="url" name="social_snapchat" value="{{ $settings['social_snapchat'] }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition text-left" dir="ltr">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-2">تيك توك (TikTok Link)</label>
                    <input type="url" name="social_tiktok" value="{{ $settings['social_tiktok'] }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition text-left" dir="ltr">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-2">فيسبوك (Facebook Link)</label>
                    <input type="url" name="social_facebook" value="{{ $settings['social_facebook'] }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition text-left" dir="ltr">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-2">إنستجرام (Instagram Link)</label>
                    <input type="url" name="social_instagram" value="{{ $settings['social_instagram'] }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition text-left" dir="ltr">
                </div>
            </div>
        </div>

        <!-- Card: Stats -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h2 class="text-lg font-bold text-gray-700 mb-4 pb-2 border-b border-gray-50">📊 الأرقام والإحصائيات</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-2">العملاء الراضين (مثال: +500)</label>
                    <input type="text" name="stat_satisfied_clients" value="{{ $settings['stat_satisfied_clients'] }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-2">سنوات الخبرة (مثال: +10)</label>
                    <input type="text" name="stat_experience_years" value="{{ $settings['stat_experience_years'] }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-2">حلال معتمد (مثال: 100%)</label>
                    <input type="text" name="stat_halal_certified" value="{{ $settings['stat_halal_certified'] }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-2">تكرار الذبح والتجهيز (مثال: يومي)</label>
                    <input type="text" name="stat_daily_slaughter" value="{{ $settings['stat_daily_slaughter'] }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition">
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl transition duration-150">إلغاء</a>
            <button type="submit" class="px-6 py-3 bg-primary hover:bg-primary-dark text-white font-bold rounded-xl shadow transition duration-150 cursor-pointer">حفظ الإعدادات</button>
        </div>
    </form>
</div>
@endsection
