@extends('layouts.app')

@section('title', 'إدارة الحساب - لحمكس')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center gap-4">
                <span class="text-5xl">👤</span>
                <div>
                    <h1 class="text-4xl font-extrabold text-primary">{{ auth()->user()->name }}</h1>
                    <p class="text-gray-600 mt-1">إدارة حسابك الشخصي</p>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="flex gap-4 mb-8 border-b border-red-100" x-data="{ activeTab: 'profile' }">
            <button @click="activeTab = 'profile'" 
                    :class="activeTab === 'profile' ? 'border-b-4 border-primary text-primary' : 'text-gray-600 hover:text-gray-800'"
                    class="pb-4 font-bold px-4 transition duration-150">
                📋 بيانات الحساب
            </button>
            <button @click="activeTab = 'password'" 
                    :class="activeTab === 'password' ? 'border-b-4 border-primary text-primary' : 'text-gray-600 hover:text-gray-800'"
                    class="pb-4 font-bold px-4 transition duration-150">
                🔐 تغيير كلمة المرور
            </button>
            <button @click="activeTab = 'orders'" 
                    :class="activeTab === 'orders' ? 'border-b-4 border-primary text-primary' : 'text-gray-600 hover:text-gray-800'"
                    class="pb-4 font-bold px-4 transition duration-150">
                📦 طلباتي
            </button>
        </div>

        <!-- Profile Tab -->
        <div x-show="activeTab === 'profile'" x-transition>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Profile Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-red-50">
                        <div class="h-2 bg-primary"></div>
                        <div class="p-8 text-center">
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="w-24 h-24 rounded-full object-cover mx-auto mb-4 border-2 border-primary shadow-md">
                            @else
                                <div class="w-24 h-24 rounded-full bg-red-50 border border-red-100 flex items-center justify-center text-4xl mx-auto mb-4">🥩</div>
                            @endif
                            <h3 class="text-xl font-bold text-primary mb-2">{{ auth()->user()->name }}</h3>
                            <p class="text-gray-600 text-sm mb-6">عضو منذ {{ auth()->user()->created_at->format('d/m/Y') }}</p>
                            
                            <div class="space-y-4">
                                <div class="bg-red-50 rounded-lg p-4 text-right">
                                    <p class="text-xs text-gray-600 mb-1">البريد الإلكتروني</p>
                                    <p class="font-semibold text-primary text-sm break-all">{{ auth()->user()->email }}</p>
                                </div>
                                <div class="bg-red-50 rounded-lg p-4 text-right">
                                    <p class="text-xs text-gray-600 mb-1">رقم الجوال</p>
                                    <p class="font-semibold text-primary text-sm">{{ auth()->user()->phone }}</p>
                                </div>
                                <div class="bg-red-50 rounded-lg p-4 text-right">
                                    <p class="text-xs text-gray-600 mb-1">حالة الحساب</p>
                                    <p class="font-semibold text-green-600 text-sm">✓ نشط</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Profile Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-red-50">
                        <div class="h-2 bg-primary"></div>
                        <div class="p-8">
                            <h2 class="text-2xl font-bold text-primary mb-6">تعديل بيانات الحساب</h2>

                            <form action="{{ route('profile.update') }}" method="POST" id="profileForm" enctype="multipart/form-data" class="space-y-6" x-data="{ photoName: null, photoPreview: null }">
                                @csrf
                                @method('PUT')

                                <!-- Profile Picture (Avatar) -->
                                <div class="flex flex-col items-center justify-center mb-6">
                                    <label class="block text-sm font-bold text-text mb-2 text-center">الصورة الشخصية</label>
                                    
                                    <input type="file" name="avatar" id="profile_avatar" class="hidden" accept="image/*"
                                           x-ref="photo"
                                           @change="
                                                photoName = $refs.photo.files[0].name;
                                                const reader = new FileReader();
                                                reader.onload = (e) => {
                                                    photoPreview = e.target.result;
                                                };
                                                reader.readAsDataURL($refs.photo.files[0]);
                                           ">

                                    <div class="relative group cursor-pointer" @click="$refs.photo.click()">
                                        <!-- Default/Current State -->
                                        <div x-show="!photoPreview" class="w-24 h-24 rounded-full overflow-hidden border-2 border-primary shadow-sm flex items-center justify-center bg-red-50">
                                            @if(auth()->user()->avatar)
                                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-full h-full object-cover">
                                            @else
                                                <span class="text-4xl">🥩</span>
                                            @endif
                                        </div>

                                        <!-- Preview State -->
                                        <div x-show="photoPreview" class="w-24 h-24 rounded-full overflow-hidden border-2 border-primary shadow-sm" style="display: none;">
                                            <img :src="photoPreview" class="w-full h-full object-cover">
                                        </div>

                                        <!-- Hover Overlay -->
                                        <div class="absolute inset-0 bg-black/40 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-150">
                                            <i class="fa-solid fa-camera text-white text-lg"></i>
                                        </div>
                                    </div>
                                    @error('avatar')
                                        <span class="text-red-600 text-xs font-semibold mt-1 block text-center">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Name -->
                                <div>
                                    <label for="name" class="block text-sm font-bold text-text mb-2">الاسم الكامل</label>
                                    <input type="text" name="name" id="name" value="{{ auth()->user()->name }}" required
                                           class="w-full px-4 py-3 rounded-lg border border-red-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-right"
                                           placeholder="أدخل اسمك الكامل">
                                    @error('name')
                                        <span class="text-red-600 text-xs font-semibold mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-bold text-text mb-2">البريد الإلكتروني</label>
                                    <input type="email" name="email" id="email" value="{{ auth()->user()->email }}" required
                                           class="w-full px-4 py-3 rounded-lg border border-red-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-right"
                                           placeholder="أدخل بريدك الإلكتروني">
                                    @error('email')
                                        <span class="text-red-600 text-xs font-semibold mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label for="phone" class="block text-sm font-bold text-text mb-2">رقم الجوال</label>
                                    <input type="text" name="phone" id="phone" value="{{ auth()->user()->phone }}" required
                                           class="w-full px-4 py-3 rounded-lg border border-red-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-right"
                                           placeholder="05xxxxxxxx">
                                    @error('phone')
                                        <span class="text-red-600 text-xs font-semibold mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <div class="flex gap-4 pt-6">
                                    <button type="submit" class="flex-1 bg-primary hover:bg-primary-dark text-white font-extrabold py-3 px-4 rounded-xl transition duration-150 shadow-md">
                                        💾 حفظ التغييرات
                                    </button>
                                    <a href="{{ route('home') }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-extrabold py-3 px-4 rounded-xl transition duration-150 shadow-md text-center">
                                        إلغاء
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Password Tab -->
        <div x-show="activeTab === 'password'" x-transition>
            <div class="max-w-2xl mx-auto">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-red-50">
                    <div class="h-2 bg-primary"></div>
                    <div class="p-8">
                        <h2 class="text-2xl font-bold text-primary mb-2">تغيير كلمة المرور</h2>
                        <p class="text-gray-600 mb-8">اختر كلمة مرور قوية لحماية حسابك</p>

                        <form action="{{ route('profile.updatePassword') }}" method="POST" id="passwordForm" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <!-- Current Password -->
                            <div>
                                <label for="current_password" class="block text-sm font-bold text-text mb-2">كلمة المرور الحالية</label>
                                <input type="password" name="current_password" id="current_password" required
                                       class="w-full px-4 py-3 rounded-lg border border-red-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-right"
                                       placeholder="أدخل كلمة المرور الحالية">
                                @error('current_password')
                                    <span class="text-red-600 text-xs font-semibold mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- New Password -->
                            <div>
                                <label for="password" class="block text-sm font-bold text-text mb-2">كلمة المرور الجديدة</label>
                                <input type="password" name="password" id="password" required
                                       class="w-full px-4 py-3 rounded-lg border border-red-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-right"
                                       placeholder="أدخل كلمة مرور جديدة">
                                <p class="text-xs text-gray-500 mt-2">يجب أن تكون الكلمة قوية وتحتوي على أحرف وأرقام</p>
                                @error('password')
                                    <span class="text-red-600 text-xs font-semibold mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-bold text-text mb-2">تأكيد كلمة المرور</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" required
                                       class="w-full px-4 py-3 rounded-lg border border-red-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-right"
                                       placeholder="أعد إدخال كلمة المرور الجديدة">
                                @error('password_confirmation')
                                    <span class="text-red-600 text-xs font-semibold mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="flex gap-4 pt-6">
                                <button type="submit" class="flex-1 bg-primary hover:bg-primary-dark text-white font-extrabold py-3 px-4 rounded-xl transition duration-150 shadow-md">
                                    🔐 تحديث كلمة المرور
                                </button>
                                <button type="reset" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-extrabold py-3 px-4 rounded-xl transition duration-150 shadow-md">
                                    مسح
                                </button>
                            </div>
                        </form>

                        <!-- Password Strength Indicator -->
                        <div class="mt-8 p-4 bg-red-50 rounded-lg border border-red-100">
                            <p class="text-sm font-bold text-primary mb-3">✓ متطلبات كلمة مرور قوية:</p>
                            <ul class="text-xs text-gray-700 space-y-2 mr-4">
                                <li>✓ 8 أحرف على الأقل</li>
                                <li>✓ تحتوي على أحرف كبيرة وصغيرة</li>
                                <li>✓ تحتوي على أرقام وأحرف خاصة</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Tab -->
        <div x-show="activeTab === 'orders'" x-transition>
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-red-50">
                <div class="h-2 bg-primary"></div>
                <div class="p-8">
                    <h2 class="text-2xl font-bold text-primary mb-6">طلباتي السابقة</h2>

                    @if(auth()->user()->orders->count() > 0)
                        <div class="space-y-4">
                            @foreach(auth()->user()->orders->sortByDesc('created_at') as $order)
                                <div class="border border-red-100 rounded-lg p-6 hover:border-primary transition">
                                    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
                                        <div>
                                            <h3 class="font-bold text-lg text-primary">
                                                الطلب #{{ $order->id }}
                                            </h3>
                                            <p class="text-sm text-gray-600 mt-1">
                                                📅 {{ $order->created_at->format('d/m/Y H:i') }}
                                            </p>
                                        </div>
                                        <div class="flex items-center gap-8">
                                            <div class="text-right">
                                                <p class="text-xs text-gray-600 mb-1">إجمالي المبلغ</p>
                                                <p class="text-xl font-bold text-primary">{{ number_format($order->total, 2) }} ر.س</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-xs text-gray-600 mb-1">الحالة</p>
                                                <span class="inline-block px-4 py-2 rounded-full text-sm font-bold
                                                    @if($order->status === 'delivered')
                                                        bg-green-100 text-green-800
                                                    @elseif($order->status === 'pending')
                                                        bg-yellow-100 text-yellow-800
                                                    @elseif($order->status === 'processing')
                                                        bg-blue-100 text-blue-800
                                                    @elseif($order->status === 'cancelled')
                                                        bg-red-100 text-red-800
                                                    @endif
                                                ">
                                                    @switch($order->status)
                                                        @case('pending')
                                                            قيد الانتظار
                                                            @break
                                                        @case('processing')
                                                            قيد المعالجة
                                                            @break
                                                        @case('delivered')
                                                            تم التسليم
                                                            @break
                                                        @case('cancelled')
                                                            ملغى
                                                            @break
                                                        @default
                                                            {{ $order->status }}
                                                    @endswitch
                                                </span>
                                            </div>
                                            <a href="{{ route('orders.show', $order->id) }}" class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-6 rounded-lg transition duration-150">
                                                عرض التفاصيل
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <span class="text-5xl block mb-4">📦</span>
                            <p class="text-gray-600 text-lg mb-6">لم تقم بأي طلبات حتى الآن</p>
                            <a href="{{ route('products.index') }}" class="bg-primary hover:bg-primary-dark text-white font-bold py-3 px-8 rounded-lg transition duration-150 inline-block">
                                👉 ابدأ التسوق الآن
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="mt-12">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-red-200">
                <div class="h-2 bg-red-600"></div>
                <div class="p-8">
                    <h2 class="text-2xl font-bold text-red-600 mb-4">⚠️ منطقة الخطر</h2>
                    <p class="text-gray-600 mb-6">هذه الإجراءات لا يمكن التراجع عنها</p>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <form action="{{ route('logout') }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-6 rounded-lg transition duration-150">
                                🚪 تسجيل الخروج
                            </button>
                        </form>
                        
                        <button onclick="if(confirm('هل أنت متأكد من حذف حسابك؟ هذا الإجراء لا يمكن التراجع عنه.')) { document.getElementById('deleteAccountForm').submit(); }" 
                                class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition duration-150">
                            🗑️ حذف الحساب نهائياً
                        </button>
                        
                        <form action="{{ route('profile.destroy') }}" method="POST" id="deleteAccountForm" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-bg-custom {
        background-color: #f9fafb;
    }
    
    .text-text-custom {
        color: #1f2937;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Profile form submission feedback
        const profileForm = document.getElementById('profileForm');
        if (profileForm) {
            profileForm.addEventListener('submit', function() {
                Swal.fire({
                    icon: 'info',
                    title: 'جاري الحفظ',
                    text: 'يتم تحديث بيانات حسابك...',
                    allowOutsideClick: false,
                    didOpen: (modal) => {
                        Swal.showLoading();
                    }
                });
            });
        }

        // Password form submission feedback
        const passwordForm = document.getElementById('passwordForm');
        if (passwordForm) {
            passwordForm.addEventListener('submit', function() {
                Swal.fire({
                    icon: 'info',
                    title: 'جاري التحديث',
                    text: 'يتم تحديث كلمة المرور...',
                    allowOutsideClick: false,
                    didOpen: (modal) => {
                        Swal.showLoading();
                    }
                });
            });
        }
    });
</script>
@endsection