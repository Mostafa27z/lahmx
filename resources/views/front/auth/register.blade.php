@extends('layouts.auth')

@section('title', 'حساب جديد - لحمكس')

@section('content')
<div class="w-full text-center mt-2">
    <h2 class="text-2xl sm:text-3xl font-extrabold text-[#2D1B1B] mb-2">سجّل باستخدام البريد الإلكتروني</h2>
    <div class="mb-8">
        <span class="text-sm font-semibold text-gray-500">هل لديك حساب بالفعل؟</span>
        <a href="{{ route('login') }}" class="text-sm font-bold text-secondary hover:text-primary hover:underline mr-1 decoration-2">تسجيل الدخول</a>
    </div>

    <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" class="space-y-5 w-full max-w-lg mx-auto text-right" x-data="{ avatarPreview: null }">
        @csrf

        <!-- Profile Picture Upload (dashed box) -->
        <div class="flex flex-col items-center justify-center mb-4">
            <input type="file" name="avatar" id="avatar" class="hidden" accept="image/*" x-ref="avatarInput"
                   @change="
                       const file = $refs.avatarInput.files[0];
                       if (file) {
                           const reader = new FileReader();
                           reader.onload = (e) => { avatarPreview = e.target.result; };
                           reader.readAsDataURL(file);
                       }
                   ">
            
            <div @click="$refs.avatarInput.click()" 
                 class="w-full h-32 rounded-2xl border-2 border-dashed border-gray-200 hover:border-primary bg-white flex flex-col items-center justify-center cursor-pointer p-4 transition-all duration-200 relative overflow-hidden group select-none">
                
                <!-- Preview State -->
                <div x-show="avatarPreview" class="absolute inset-0 z-10 bg-white">
                    <img :src="avatarPreview" class="w-full h-full object-cover">
                    <!-- Hover overlay to change -->
                    <div class="absolute inset-0 bg-black/45 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-150">
                        <i class="fa-solid fa-camera text-white text-2xl"></i>
                    </div>
                </div>

                <!-- Default State -->
                <div x-show="!avatarPreview" class="flex flex-col items-center text-center">
                    <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 group-hover:text-primary transition-colors mb-2">
                        <i class="fa-solid fa-camera text-lg"></i>
                    </div>
                    <span class="text-sm font-bold text-gray-700">الصورة الشخصية</span>
                    <span class="text-xs text-gray-400 mt-1">JPG, PNG • حتى 2 ميجابايت</span>
                </div>
            </div>
            @error('avatar')
                <span class="text-red-500 text-xs font-semibold mt-1.5 block text-center">{{ $message }}</span>
            @enderror
        </div>

        <!-- Name and Phone Inputs (Side by Side) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Name Input -->
            <div>
                <x-custom-input type="text" name="name" label="الاسم الكامل" icon="fa-regular fa-user" required />
                @error('name')
                    <span class="text-red-500 text-xs font-semibold mt-1.5 block pr-2">{{ $message }}</span>
                @enderror
            </div>

            <!-- Phone Input -->
            <div>
                <x-phone-input name="phone" label="رقم الجوال" required />
                @error('phone')
                    <span class="text-red-500 text-xs font-semibold mt-1.5 block pr-2">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Email Input (Full Width) -->
        <div>
            <x-custom-input type="email" name="email" label="* البريد الإلكتروني" icon="fa-regular fa-envelope" required />
            @error('email')
                <span class="text-red-500 text-xs font-semibold mt-1.5 block pr-2">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password Inputs (Side by Side) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Password Input -->
            <div>
                <x-custom-input type="password" name="password" label="* كلمة المرور" required />
                @error('password')
                    <span class="text-red-500 text-xs font-semibold mt-1.5 block pr-2">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password Confirmation Input -->
            <div>
                <x-custom-input type="password" name="password_confirmation" label="تأكيد كلمة المرور" required />
            </div>
        </div>

        <p class="text-xs text-gray-500 leading-relaxed text-center px-4 pt-2">
            بالنقر على "متابعة"، فإنك توافق على <a href="#" class="underline font-bold text-gray-700">سياسة الخصوصية</a> الخاصة بنا.
        </p>

        <!-- Submit Button -->
        <x-custom-button variant="primary">
            إنشاء حساب
        </x-custom-button>
    </form>
</div>
@endsection
