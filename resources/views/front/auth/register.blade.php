@extends('layouts.app')

@section('title', 'تسجيل حساب جديد - لحمكس')

@section('content')
<div class="py-16 bg-bg-custom flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden border border-red-50">
        <!-- Accent bar -->
        <div class="h-2 bg-primary"></div>
        
        <div class="p-8">
            <div class="text-center mb-8">
                <span class="text-4xl">🥩</span>
                <h2 class="text-3xl font-extrabold text-primary mt-4">حساب جديد</h2>
                <p class="text-gray-500 mt-2">انضم إلينا واستمتع بأجود أنواع اللحوم</p>
            </div>

            <form action="{{ route('register') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-bold text-text-custom mb-1">الاسم الكامل</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-3 rounded-lg border border-red-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-right">
                    @error('name')
                        <span class="text-red-600 text-xs font-semibold mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-bold text-text-custom mb-1">رقم الجوال</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required placeholder="05xxxxxxxx"
                           class="w-full px-4 py-3 rounded-lg border border-red-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-right">
                    @error('phone')
                        <span class="text-red-600 text-xs font-semibold mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-bold text-text-custom mb-1">البريد الإلكتروني</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 rounded-lg border border-red-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-right">
                    @error('email')
                        <span class="text-red-600 text-xs font-semibold mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-bold text-text-custom mb-1">كلمة المرور</label>
                    <input type="password" name="password" id="password" required
                           class="w-full px-4 py-3 rounded-lg border border-red-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-right">
                    @error('password')
                        <span class="text-red-600 text-xs font-semibold mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-bold text-text-custom mb-1">تأكيد كلمة المرور</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="w-full px-4 py-3 rounded-lg border border-red-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-right">
                </div>

                <!-- Submit -->
                <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-extrabold py-3 px-4 rounded-xl transition duration-150 shadow-md">
                    تسجيل الحساب
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-red-50 text-center">
                <span class="text-sm text-gray-500">لديك حساب بالفعل؟</span>
                <a href="{{ route('login') }}" class="text-sm font-bold text-secondary hover:underline mr-1">سجل دخولك هنا</a>
            </div>
        </div>
    </div>
</div>
@endsection
