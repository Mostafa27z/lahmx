@extends('layouts.app')

@section('title', 'تسجيل الدخول - لحمكس')

@section('content')
<div class="py-16 bg-bg-custom flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden border border-red-50">
        <!-- Accent bar -->
        <div class="h-2 bg-primary"></div>
        
        <div class="p-8">
            <div class="text-center mb-8">
                <span class="text-4xl">🥩</span>
                <h2 class="text-3xl font-extrabold text-primary mt-4">تسجيل الدخول</h2>
                <p class="text-gray-500 mt-2">أهلاً بك مجدداً في متجر لحمكس</p>
            </div>

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-bold text-text-custom mb-2">البريد الإلكتروني</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                           class="w-full px-4 py-3 rounded-lg border border-red-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-right">
                    @error('email')
                        <span class="text-red-600 text-xs font-semibold mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-bold text-text-custom mb-2">كلمة المرور</label>
                    <input type="password" name="password" id="password" required
                           class="w-full px-4 py-3 rounded-lg border border-red-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-right">
                    @error('password')
                        <span class="text-red-600 text-xs font-semibold mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-red-200 text-primary focus:ring-primary">
                        <span class="text-sm font-semibold text-gray-600">تذكرني</span>
                    </label>
                </div>

                <!-- Submit -->
                <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-extrabold py-3 px-4 rounded-xl transition duration-150 shadow-md">
                    دخول
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-red-50 text-center">
                <span class="text-sm text-gray-500">ليس لديك حساب؟</span>
                <a href="{{ route('register') }}" class="text-sm font-bold text-secondary hover:underline mr-1">سجل حساباً جديداً</a>
            </div>
        </div>
    </div>
</div>
@endsection
