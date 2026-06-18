@extends('layouts.auth')

@section('title', 'تسجيل الدخول - لحمكس')

@section('content')
<div class="w-full text-center mt-2">
    <h2 class="text-2xl sm:text-3xl font-extrabold text-[#2D1B1B] mb-2">تسجيل الدخول إلى حسابك</h2>
    <div class="mb-8">
        <span class="text-sm font-semibold text-gray-500">ليس لديك حساب بعد؟</span>
        <a href="{{ route('register') }}" class="text-sm font-bold text-secondary hover:text-primary hover:underline mr-1 decoration-2">إنشاء حساب</a>
    </div>

    <form action="{{ route('login') }}" method="POST" class="space-y-6 w-full max-w-md mx-auto text-right">
        @csrf

        <!-- Email Input -->
        <div>
            <x-custom-input type="email" name="email" label="* البريد الإلكتروني" icon="fa-regular fa-envelope" required autofocus />
            @error('email')
                <span class="text-red-500 text-xs font-semibold mt-1.5 block text-right pr-2">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password Input -->
        <div>
            <x-custom-input type="password" name="password" label="* كلمة المرور" required />
            @error('password')
                <span class="text-red-500 text-xs font-semibold mt-1.5 block text-right pr-2">{{ $message }}</span>
            @enderror
        </div>

        <!-- Forgot Password Link -->
        <div class="text-right">
            <a href="#" class="text-xs font-bold text-secondary hover:text-primary transition">نسيت كلمة المرور؟</a>
        </div>

        <p class="text-xs text-gray-500 leading-relaxed text-center px-4">
            بالنقر على "متابعة"، فإنك توافق على <a href="#" class="underline font-bold text-gray-700">سياسة الخصوصية</a> الخاصة بنا.
        </p>

        <!-- Submit Button -->
        <x-custom-button variant="primary">
            تسجيل الدخول
        </x-custom-button>
    </form>
</div>
@endsection
