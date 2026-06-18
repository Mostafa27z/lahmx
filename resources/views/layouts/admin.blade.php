<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم - لحمكس')</title>
    
    <!-- Google Fonts: Cairo -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS & Vite -->
    @vite(['resources/css/app.css'])

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #fcfcfc;
            color: #2D1B1B;
        }
    </style>
    @yield('styles')
</head>
<body class="min-h-screen flex bg-gray-50">

    <!-- Sidebar -->
    <aside class="w-64 bg-primary-dark text-white flex flex-col shadow-xl hidden md:flex">
        <!-- Brand logo -->
        <div class="h-20 flex items-center justify-center border-b border-red-950 bg-primary-dark px-6">
            <a href="{{ route('home') }}" class="flex items-center py-2">
                <img src="https://res.cloudinary.com/dmma4cjad/image/upload/v1781767352/1b04de4f-92ea-43ce-812a-3fe180240ab8_hieejs.png" alt="لحمكس" class="h-12 w-auto object-contain brightness-110 contrast-110">
            </a>
        </div>
        
        <!-- Navigation Menu -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg font-bold transition {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-white shadow' : 'hover:bg-red-900 text-red-100' }}">
                <span>📊</span> الإحصائيات
            </a>
            
            <a href="{{ route('admin.categories.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg font-bold transition {{ request()->routeIs('admin.categories.*') ? 'bg-primary text-white shadow' : 'hover:bg-red-900 text-red-100' }}">
                <span>📁</span> إدارة الفئات
            </a>
            
            <a href="{{ route('admin.products.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg font-bold transition {{ request()->routeIs('admin.products.*') ? 'bg-primary text-white shadow' : 'hover:bg-red-900 text-red-100' }}">
                <span>🥩</span> إدارة المنتجات
            </a>
            
            <a href="{{ route('admin.orders.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg font-bold transition {{ request()->routeIs('admin.orders.*') ? 'bg-primary text-white shadow' : 'hover:bg-red-900 text-red-100' }}">
                <span>📦</span> إدارة الطلبات
            </a>
            
            <a href="{{ route('admin.customers.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg font-bold transition {{ request()->routeIs('admin.customers.*') ? 'bg-primary text-white shadow' : 'hover:bg-red-900 text-red-100' }}">
                <span>👥</span> العملاء
            </a>
            
            <a href="{{ route('admin.payments.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg font-bold transition {{ request()->routeIs('admin.payments.*') ? 'bg-primary text-white shadow' : 'hover:bg-red-900 text-red-100' }}">
                <span>💳</span> العمليات المالية
            </a>
            
            <a href="{{ route('admin.settings.edit') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg font-bold transition {{ request()->routeIs('admin.settings.*') ? 'bg-primary text-white shadow' : 'hover:bg-red-900 text-red-100' }}">
                <span>⚙️</span> إعدادات الشركة
            </a>
        </nav>

        <!-- Footer sidebar -->
        <div class="p-4 border-t border-red-950 bg-red-950">
            <div class="flex items-center justify-between">
                <div class="text-sm font-semibold truncate">{{ auth()->user()->name }}</div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="تسجيل الخروج" class="p-1 rounded text-red-300 hover:text-white transition">
                        🚪
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Container -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Header -->
        <header class="bg-white border-b border-gray-100 h-20 flex items-center justify-between px-6 shadow-sm z-10">
            <button class="md:hidden p-2 text-gray-500 hover:text-gray-700">
                Menu
            </button>
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" target="_blank" class="text-sm font-bold text-primary hover:underline">
                    زيارة المتجر 🌐
                </a>
            </div>
        </header>

        <!-- Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
            @yield('content')
        </main>
    </div>

    <!-- Notification Scripts using SweetAlert2 -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'نجاح',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'حسناً',
                    confirmButtonColor: '#7A0C0C',
                    timer: 3000
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'حسناً',
                    confirmButtonColor: '#7A0C0C'
                });
            @endif
        });
    </script>
    @yield('scripts')
</body>
</html>
