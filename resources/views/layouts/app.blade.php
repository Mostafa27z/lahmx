<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لحمكس - متجر اللحوم الفاخرة')</title>
    
    <!-- Google Fonts: Cairo -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS & Vite -->
    @vite(['resources/css/app.css'])

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --primary: #7A0C0C;
            --primary-dark: #540909;
            --secondary: #B22222;
            --accent: #D4A373;
            --background: #FFF8F6;
            --text: #2D1B1B;
            --success: #2E8B57;
            --danger: #DC2626;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background-color: var(--background);
            color: var(--text);
        }

        .text-primary {
            color: var(--primary);
        }

        .text-text {
            color: var(--text);
        }

        .bg-white {
            background-color: white;
        }

        .bg-primary-dark {
            background-color: var(--primary-dark);
        }

        .text-secondary {
            color: var(--secondary);
        }

        .bg-secondary {
            background-color: var(--secondary);
        }

        .border-red-100 {
            border-color: rgba(122, 12, 12, 0.15);
        }

        .border-red-50 {
            border-color: rgba(122, 12, 12, 0.08);
        }

        .bg-red-50 {
            background-color: rgba(122, 12, 12, 0.08);
        }

        .text-red-200 {
            color: rgba(212, 163, 115, 0.8);
        }

        .text-red-100 {
            color: rgba(212, 163, 115, 0.9);
        }

        .text-red-300 {
            color: rgba(212, 163, 115, 0.7);
        }

        .text-red-900 {
            border-color: rgba(122, 12, 12, 0.3);
        }

        .text-red-600 {
            color: var(--danger);
        }

        .border-accent {
            border-color: var(--accent);
        }

        .text-accent {
            color: var(--accent);
        }
    </style>
    @yield('styles')
</head>
<body class="min-h-screen flex flex-col" x-data="{ cartCount: {{ \App\Models\Cart::where('user_id', auth()->id())->orWhere('id', session('guest_cart_id'))->first()?->items_count ?? 0 }} }">

    <!-- Header Navigation -->
    <header class="bg-white border-b shadow-sm sticky top-0 z-50" style="border-color: rgba(122, 12, 12, 0.15);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-3">
                        <span class="text-3xl">🥩</span>
                        <span class="text-2xl font-extrabold text-primary tracking-wide">لحمكس</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <nav class="hidden md:flex space-x-reverse space-x-8">
                    <a href="{{ route('home') }}" class="text-text font-bold hover:text-primary transition duration-150">الرئيسية</a>
                    <a href="{{ route('products.index') }}" class="text-text font-bold hover:text-primary transition duration-150">متجرنا</a>
                    <a href="{{ route('products.index', ['category_id' => 1]) }}" class="text-text font-semibold hover:text-primary transition duration-150">لحوم أبقار</a>
                    <a href="{{ route('products.index', ['category_id' => 2]) }}" class="text-text font-semibold hover:text-primary transition duration-150">لحوم أغنام</a>
                </nav>

                <!-- Action Buttons -->
                <div class="flex items-center gap-4">
                    <!-- Cart Indicator -->
                    <a href="{{ route('cart.index') }}" class="relative p-2 text-text hover:text-primary transition duration-150">
                        <span class="text-2xl">🛒</span>
                        <span class="absolute -top-1 -right-1 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center" 
                              x-text="cartCount" 
                              x-show="cartCount > 0"
                              style="background-color: var(--secondary);">
                        </span>
                    </a>

                    <!-- Auth Dropdown/Links -->
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 font-bold text-text hover:text-primary transition duration-150 focus:outline-none">
                                <span>👤</span>
                                <span>{{ auth()->user()->name }}</span>
                            </button>
                            <!-- Dropdown Menu -->
                            <div x-show="open" @click.away="open = false" 
                                 class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50"
                                 style="border: 1px solid rgba(122, 12, 12, 0.15);"
                                 x-transition>
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-text hover:font-semibold transition" style="background-color: rgba(122, 12, 12, 0.08);">لوحة التحكم 🛠️</a>
                                @endif
                                <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-text hover:font-semibold transition" style="background-color: rgba(122, 12, 12, 0.08);">حسابي 👤</a>
                                <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-sm text-text hover:font-semibold transition" style="background-color: rgba(122, 12, 12, 0.08);">طلباتي 📦</a>
                                <form action="{{ route('logout') }}" method="POST" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-right px-4 py-2 text-sm font-semibold transition" style="color: var(--danger); background-color: rgba(220, 38, 38, 0.08);">تسجيل الخروج 🚪</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-white px-5 py-2 rounded-lg font-bold transition duration-150" style="background-color: var(--primary);">تسجيل الدخول</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="text-white pt-12 pb-8" style="background-color: var(--primary-dark); border-top: 4px solid var(--accent);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-size grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Info -->
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="text-3xl">🥩</span>
                        <span class="text-2xl font-bold tracking-wide text-white">لحمكس</span>
                    </div>
                    <p class="text-sm leading-relaxed" style="color: rgba(212, 163, 115, 0.9);">
                        متجر لحمكس هو خيارك الأمثل للحصول على أجود أنواع اللحوم الطازجة والفاخرة المنتقاة بعناية فائقة لتصل إلى باب منزلك بأعلى معايير الجودة والسلامة.
                    </p>
                </div>
                <!-- Quick links -->
                <div>
                    <h3 class="text-lg font-bold mb-4 text-accent border-r-4 pr-3" style="border-color: var(--accent);">روابط سريعة</h3>
                    <ul class="space-y-2 text-sm" style="color: rgba(212, 163, 115, 0.95);">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition">الرئيسية</a></li>
                        <li><a href="{{ route('products.index') }}" class="hover:text-white transition">جميع المنتجات</a></li>
                        <li><a href="{{ route('cart.index') }}" class="hover:text-white transition">سلة المشتريات</a></li>
                    </ul>
                </div>
                <!-- Contact info -->
                <div>
                    <h3 class="text-lg font-bold mb-4 text-accent border-r-4 pr-3" style="border-color: var(--accent);">تواصل معنا</h3>
                    <ul class="space-y-2 text-sm" style="color: rgba(212, 163, 115, 0.95);">
                        <li>📍 المملكة العربية السعودية</li>
                        <li>📞 +966 500 000 000</li>
                        <li>✉️ info@lahmax.com</li>
                    </ul>
                </div>
            </div>
            
            <div class="mt-8 pt-6 text-center text-xs" style="border-top: 1px solid rgba(122, 12, 12, 0.3); color: rgba(212, 163, 115, 0.8);">
                &copy; {{ date('Y') }} لحمكس. جميع الحقوق محفوظة.
            </div>
        </div>
    </footer>

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