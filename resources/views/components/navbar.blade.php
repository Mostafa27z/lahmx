<!-- Header Navigation -->
<header class="bg-white border-b shadow-sm sticky top-0 z-50" style="border-color: rgba(122, 12, 12, 0.15);" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-14">
            <!-- Mobile Menu Trigger (Left on Mobile, Hidden on Desktop) -->
            <div class="flex items-center md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-text hover:text-primary focus:outline-none p-2" aria-label="Toggle menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <!-- Logo (Left on Desktop, Centered on Mobile) -->
            <div class="flex items-center justify-center md:justify-start flex-grow md:flex-grow-0">
                <a href="{{ route('home') }}" class="flex items-center py-1">
                    <img src="https://res.cloudinary.com/dmma4cjad/image/upload/v1781767352/1b04de4f-92ea-43ce-812a-3fe180240ab8_hieejs.png" alt="لحمكس - Lahmix" class="h-12 w-auto object-contain">
                </a>
            </div>

            <!-- Navigation Links (Desktop - RTL order) -->
            <nav class="hidden md:flex items-center h-full space-x-reverse space-x-4 lg:space-x-6">
                <a href="{{ Route::currentRouteName() == 'home' ? '#' : route('home') }}" data-scroll-spy="#" class="nav-link text-text font-semibold hover:text-primary transition-all duration-150 border-t-2 {{ Route::currentRouteName() == 'home' ? 'border-primary text-primary' : 'border-transparent' }} h-full flex items-center pt-0.5 text-xs lg:text-sm">الرئيسية</a>
                <a href="{{ route('about') }}" class="nav-link text-text font-semibold hover:text-primary transition-all duration-150 border-t-2 {{ Route::currentRouteName() == 'about' ? 'border-primary text-primary' : 'border-transparent' }} h-full flex items-center pt-0.5 text-xs lg:text-sm">من نحن</a>
                <a href="{{ Route::currentRouteName() == 'home' ? '#products-section' : route('products.index') }}" data-scroll-spy="#products-section" class="nav-link text-text font-semibold hover:text-primary transition-all duration-150 border-t-2 {{ Route::currentRouteName() == 'products.index' ? 'border-primary text-primary' : 'border-transparent' }} h-full flex items-center pt-0.5 text-xs lg:text-sm">منتجاتنا</a>
                <a href="{{ Route::currentRouteName() == 'home' ? '#categories' : route('home') . '#categories' }}" data-scroll-spy="#categories" class="nav-link text-text font-semibold hover:text-primary transition-all duration-150 border-t-2 border-transparent h-full flex items-center pt-0.5 text-xs lg:text-sm">المواشي</a>
                @auth
                    <a href="{{ route('orders.index') }}" class="nav-link text-text font-semibold hover:text-primary transition-all duration-150 border-t-2 {{ Route::currentRouteName() == 'orders.index' ? 'border-primary text-primary' : 'border-transparent' }} h-full flex items-center pt-0.5 text-xs lg:text-sm">الطلبات</a>
                @endauth
                <a href="{{ Route::currentRouteName() == 'home' ? '#blog' : route('home') . '#blog' }}" data-scroll-spy="#blog" class="nav-link text-text font-semibold hover:text-primary transition-all duration-150 border-t-2 border-transparent h-full flex items-center pt-0.5 text-xs lg:text-sm">المدونة</a>
                <a href="{{ Route::currentRouteName() == 'home' ? '#contact' : route('home') . '#contact' }}" data-scroll-spy="#contact" class="nav-link text-text font-semibold hover:text-primary transition-all duration-150 border-t-2 border-transparent h-full flex items-center pt-0.5 text-xs lg:text-sm">تواصل معنا</a>
            </nav>

            <!-- Action Buttons (Right) -->
            <div class="flex items-center gap-2 md:gap-4">
                {{-- Wishlist Heart Icon --}}
                <a href="/wishlist"
                   class="relative p-2 hover:text-red-500 transition duration-150"
                   aria-label="المفضلة">
                    <i class="fa-regular fa-heart text-xl" style="color:#7a0c0c;"></i>
                    <span class="absolute -top-0.5 -right-0.5 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center"
                          x-text="wishlistCount"
                          x-show="wishlistCount > 0"
                          style="background-color: #e11d48; font-size: 10px;">
                    </span>
                </a>

                {{-- Cart Indicator --}}
                <a href="{{ route('cart.index') }}" class="relative p-2 text-text hover:text-primary transition duration-150" aria-label="Shopping Cart">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span class="absolute -top-0.5 -right-0.5 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center" 
                          x-text="cartCount" 
                          x-show="cartCount > 0"
                          style="background-color: var(--secondary);">
                    </span>
                </a>

                <!-- Auth Dropdown/Links -->
                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 font-bold text-text hover:text-primary transition duration-150 focus:outline-none text-sm md:text-base cursor-pointer">
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-8 h-8 rounded-full object-cover border border-primary/20 shadow-xs">
                            @else
                                <span class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center text-sm font-bold border border-primary/20 shadow-xs">
                                    {{ mb_substr(auth()->user()->name, 0, 1) }}
                                </span>
                            @endif
                            <span class="hidden md:inline">{{ auth()->user()->name }}</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        <!-- Dropdown Menu -->
                        <div x-show="open" @click.away="open = false" 
                             class="absolute left-0 mt-3 w-72 bg-white rounded-2xl shadow-xl py-1 z-50 text-right overflow-hidden border"
                             style="border-color: rgba(122, 12, 12, 0.08);"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 -translate-y-2">
                             
                            <!-- Dropdown Header -->
                            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/70">
                                <p class="text-xs font-bold text-gray-400 mb-1">مرحباً بك،</p>
                                <p class="text-sm font-extrabold text-gray-800 truncate">{{ auth()->user()->name }}</p>
                            </div>
                            
                            <!-- Menu Items -->
                            <div class="p-2 space-y-0.5">
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-between px-4 py-3 rounded-xl text-sm font-bold text-gray-700 hover:bg-primary/5 hover:text-primary transition group">
                                        <span class="flex items-center gap-3">
                                            <i class="fa-solid fa-screwdriver-wrench text-primary/70 group-hover:text-primary transition-colors text-base"></i>
                                            <span>لوحة التحكم</span>
                                        </span>
                                        <i class="fa-solid fa-angle-left text-xs text-gray-300 group-hover:text-primary transition-colors"></i>
                                    </a>
                                @endif
                                
                                <a href="{{ route('profile.show') }}" class="flex items-center justify-between px-4 py-3 rounded-xl text-sm font-bold text-gray-700 hover:bg-primary/5 hover:text-primary transition group">
                                    <span class="flex items-center gap-3">
                                        <i class="fa-solid fa-circle-user text-purple-500/70 group-hover:text-purple-600 transition-colors text-base"></i>
                                        <span>حسابي</span>
                                    </span>
                                    <i class="fa-solid fa-angle-left text-xs text-gray-300 group-hover:text-primary transition-colors"></i>
                                </a>
                                
                                <a href="{{ route('orders.index') }}" class="flex items-center justify-between px-4 py-3 rounded-xl text-sm font-bold text-gray-700 hover:bg-primary/5 hover:text-primary transition group">
                                    <span class="flex items-center gap-3">
                                        <i class="fa-solid fa-box-open text-amber-500/70 group-hover:text-amber-600 transition-colors text-base"></i>
                                        <span>طلباتي</span>
                                    </span>
                                    <i class="fa-solid fa-angle-left text-xs text-gray-300 group-hover:text-primary transition-colors"></i>
                                </a>
                                
                                <div class="pt-1 mt-1 border-t border-gray-100">
                                    <form action="{{ route('logout') }}" method="POST" class="block">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-red-600 hover:bg-red-50 transition text-right cursor-pointer">
                                            <i class="fa-solid fa-right-from-bracket text-red-500/70 text-base"></i>
                                            <span>تسجيل الخروج</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="hidden sm:inline-block text-white px-4 py-2 rounded-lg font-bold transition duration-150 text-sm" style="background-color: var(--primary);">تسجيل الدخول</a>
                @endauth

                <!-- Order Now Button (Desktop only) -->
                <a href="{{ route('products.index') }}" class="hidden lg:inline-flex items-center justify-center text-white px-4 py-1.5 rounded-full font-bold text-xs transition duration-150 hover:opacity-90 shadow-sm" style="background-color: var(--primary);">
                    اطلب الآن
                </a>
            </div>
        </div>
    </div>

    <!-- Mobile Drawer Menu (Slide-in from left/right) -->
    <div x-show="mobileMenuOpen" class="fixed inset-0 z-50 md:hidden" style="display: none;">
        <!-- Backdrop -->
        <div @click="mobileMenuOpen = false" class="fixed inset-0 bg-black/55 backdrop-blur-sm transition-opacity"
             x-show="mobileMenuOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"></div>

        <!-- Drawer Content -->
        <div class="fixed inset-y-0 right-0 max-w-xs w-full bg-white shadow-xl flex flex-col z-50"
             x-show="mobileMenuOpen"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full">
            
            <!-- Drawer Header -->
            <div class="flex items-center justify-between px-6 py-5 border-b border-red-50">
                <a href="{{ route('home') }}" class="flex items-center">
                    <img src="https://res.cloudinary.com/dmma4cjad/image/upload/v1781767352/1b04de4f-92ea-43ce-812a-3fe180240ab8_hieejs.png" alt="لحمكس - Lahmix" class="h-10 w-auto object-contain">
                </a>
                <button @click="mobileMenuOpen = false" class="text-text hover:text-primary p-2" aria-label="Close menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Drawer Body (Links) -->
            <div class="flex-grow py-6 px-6 overflow-y-auto">
                <nav class="flex flex-col space-y-4 text-right">
                    <a href="{{ Route::currentRouteName() == 'home' ? '#' : route('home') }}" data-scroll-spy="#" @click="mobileMenuOpen = false" class="block text-text font-bold text-lg hover:text-primary py-2 border-b border-gray-100/50">الرئيسية</a>
                    <a href="{{ route('about') }}" @click="mobileMenuOpen = false" class="block text-text font-bold text-lg hover:text-primary py-2 border-b border-gray-100/50 {{ Route::currentRouteName() == 'about' ? 'text-primary' : '' }}">من نحن</a>
                    <a href="{{ Route::currentRouteName() == 'home' ? '#products-section' : route('products.index') }}" data-scroll-spy="#products-section" @click="mobileMenuOpen = false" class="block text-text font-bold text-lg hover:text-primary py-2 border-b border-gray-100/50">منتجاتنا</a>
                    <a href="{{ Route::currentRouteName() == 'home' ? '#categories' : route('home') . '#categories' }}" data-scroll-spy="#categories" @click="mobileMenuOpen = false" class="block text-text font-bold text-lg hover:text-primary py-2 border-b border-gray-100/50">المواشي</a>
                    @auth
                        <a href="{{ route('orders.index') }}" @click="mobileMenuOpen = false" class="block text-text font-bold text-lg hover:text-primary py-2 border-b border-gray-100/50">الطلبات</a>
                    @endauth
                    <a href="{{ Route::currentRouteName() == 'home' ? '#blog' : route('home') . '#blog' }}" data-scroll-spy="#blog" @click="mobileMenuOpen = false" class="block text-text font-bold text-lg hover:text-primary py-2 border-b border-gray-100/50">المدونة</a>
                    <a href="{{ Route::currentRouteName() == 'home' ? '#contact' : route('home') . '#contact' }}" data-scroll-spy="#contact" @click="mobileMenuOpen = false" class="block text-text font-bold text-lg hover:text-primary py-2 border-b border-gray-100/50">تواصل معنا</a>

                    <!-- Extra action buttons for mobile -->
                    <div class="pt-6">
                        @guest
                            <a href="{{ route('login') }}" @click="mobileMenuOpen = false" class="w-full inline-flex items-center justify-center text-white px-5 py-3 rounded-xl font-bold transition duration-150 text-base mb-3 shadow-md" style="background-color: var(--primary);">
                                تسجيل الدخول
                            </a>
                        @endguest
                        <a href="{{ route('products.index') }}" @click="mobileMenuOpen = false" class="w-full inline-flex items-center justify-center text-white px-5 py-3 rounded-xl font-bold transition duration-150 text-base shadow-md bg-secondary" style="background-color: var(--secondary);">
                            اطلب الآن
                        </a>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</header>
