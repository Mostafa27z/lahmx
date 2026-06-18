<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لحمكس - متجر اللحوم الفاخرة')</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="https://res.cloudinary.com/dmma4cjad/image/upload/v1781767352/1b04de4f-92ea-43ce-812a-3fe180240ab8_hieejs.png">
    <link rel="shortcut icon" type="image/png" href="https://res.cloudinary.com/dmma4cjad/image/upload/v1781767352/1b04de4f-92ea-43ce-812a-3fe180240ab8_hieejs.png">
    <link rel="apple-touch-icon" href="https://res.cloudinary.com/dmma4cjad/image/upload/v1781767352/1b04de4f-92ea-43ce-812a-3fe180240ab8_hieejs.png">
    
    <!-- Google Fonts: Cairo -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS & Vite -->
    @vite(['resources/css/app.css'])

    <!-- Alpine.js + productCard component (must be before Alpine CDN) -->
    <script>
        // Global registry: productId => Alpine component reference
        window._cartCards = {};

        /**
         * productCard Alpine component
         * Tracks in-cart quantity and loading state for add-to-cart buttons.
         */
        function productCard({ productId, initialQty, isWished = false }) {
            return {
                productId: productId,
                cartQty: initialQty,
                loading: false,
                isWished: isWished,
                init() {
                    // Register so global addToCart can update this card
                    window._cartCards[this.productId] = this;
                    // Sync wishlist state from localStorage on init
                    if (window.Wishlist) {
                        this.isWished = window.Wishlist.has(this.productId);
                    }
                },
                destroy() {
                    delete window._cartCards[this.productId];
                },
                toggleWish(btn) {
                    if (!window.Wishlist) return;
                    this.isWished = window.Wishlist.toggle(this.productId);
                    // Pop animation
                    if (btn) {
                        btn.classList.remove('animate-wish');
                        void btn.offsetWidth;
                        btn.classList.add('animate-wish');
                    }
                    window.showToast && window.showToast(
                        this.isWished ? 'تمت الإضافة إلى المفضلة ❤️' : 'تمت إزالة المنتج من المفضلة',
                        this.isWished ? 'success' : 'info'
                    );
                }
            };
        }
    </script>
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

        /* Smooth Scroll */
        html {
            scroll-behavior: smooth;
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: var(--background);
        }
        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 99px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--secondary);
        }

        /* Hide scrollbar utility */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }

        /* Back to top button */
        #back-to-top {
            opacity: 0;
            transform: translateY(20px);
            pointer-events: none;
            transition: opacity 0.35s ease, transform 0.35s ease;
        }
        #back-to-top.visible {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        /* WhatsApp floating button - smooth entrance + periodic bounce */
        @keyframes wa-slide-in {
            0%   { opacity: 0; transform: translateY(30px) scale(0.85); }
            60%  { opacity: 1; transform: translateY(-6px) scale(1.04); }
            80%  { transform: translateY(3px) scale(0.98); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes wa-bounce {
            0%, 100% { transform: translateY(0) scale(1); }
            30%       { transform: translateY(-8px) scale(1.05); }
            55%       { transform: translateY(-3px) scale(1.02); }
            75%       { transform: translateY(-5px) scale(1.03); }
        }
        #whatsapp-widget {
            animation: wa-slide-in 0.65s cubic-bezier(0.34, 1.56, 0.64, 1) 0.8s both,
                       wa-bounce 2.8s ease-in-out 5s infinite;
        }
        #whatsapp-widget:hover {
            transform: scale(1.06) !important;
            transition: transform 0.2s ease !important;
        }
        /* Nav link hover: show top border */
        .nav-link:hover {
            border-top-color: var(--primary) !important;
        }
        /* Keep active state top border */
        .nav-link.border-primary {
            border-top-color: var(--primary);
        }
    </style>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    @yield('styles')
</head>
<body class="min-h-screen flex flex-col" x-data="{ cartCount: {{ \App\Models\Cart::where('user_id', auth()->id())->orWhere('id', session('guest_cart_id'))->first()?->items_count ?? 0 }}, wishlistCount: 0, initWishlist() { this.wishlistCount = window.Wishlist ? window.Wishlist.count() : 0; } }" x-init="initWishlist()">

    <!-- Header Navigation -->
    <x-navbar />

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <x-footer />

    <!-- WhatsApp Floating Button (Bottom-Right) -->
    <a href="https://wa.me/966500000000" target="_blank" rel="noopener noreferrer"
       dir="ltr"
       class="fixed bottom-6 right-6 z-50 flex items-center gap-2 group transition-all duration-300"
       id="whatsapp-widget">
        <!-- Text pill "تواصل معنا" -->
        <span class="bg-white font-bold px-3 py-1.5 rounded-full shadow-md text-xs sm:text-sm border border-gray-100/50 group-hover:bg-gray-50 transition-colors duration-200" style="color: var(--text);">
            تواصل معنا
        </span>
        <!-- WhatsApp Icon Circle with Badge -->
        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-[#25D366] text-white flex items-center justify-center shadow-lg group-hover:scale-105 active:scale-95 transition-transform duration-200 relative">
            <i class="fa-brands fa-whatsapp text-xl sm:text-2xl"></i>
            <!-- Badge "1" -->
            <span class="absolute -top-0.5 -right-0.5 bg-red-600 text-white text-[9px] font-bold rounded-full w-4 h-4 flex items-center justify-center animate-pulse">1</span>
        </div>
    </a>

    <!-- Scroll To Top Button (Bottom-Left) -->
    <button id="back-to-top"
            onclick="scrollToTopSmooth()"
            class="fixed bottom-6 left-6 z-50 w-10 h-10 rounded-full text-white flex items-center justify-center shadow-lg hover:scale-110 active:scale-95 transition-transform duration-200 cursor-pointer"
            style="background-color: var(--primary);"
            aria-label="Back to top">
        <i class="fa-solid fa-chevron-up text-base sm:text-lg"></i>
    </button>

    <script>
        /* ─── Wishlist Engine ─────────────────────────────────────────── */
        window.Wishlist = {
            _key: 'lhmx_wishlist',
            _get() {
                try { return JSON.parse(localStorage.getItem(this._key) || '[]'); } catch(e) { return []; }
            },
            _set(list) {
                localStorage.setItem(this._key, JSON.stringify(list));
                // Sync navbar counter
                const body = document.querySelector('[x-data]');
                if (body && body._x_dataStack && body._x_dataStack[0]) {
                    body._x_dataStack[0].wishlistCount = list.length;
                }
            },
            has(id) { return this._get().includes(Number(id)); },
            add(id)  { const l = this._get(); if (!l.includes(Number(id))) l.push(Number(id)); this._set(l); },
            remove(id){ this._set(this._get().filter(x => x !== Number(id))); },
            toggle(id){ this.has(id) ? this.remove(id) : this.add(id); return this.has(id); },
            count()   { return this._get().length; },
            all()     { return this._get(); }
        };

        window.toggleWishlist = function(productId, btn) {
            const isWished = window.Wishlist.toggle(productId);
            if (!btn) return;
            // Animate the button
            btn.classList.remove('animate-wish');
            void btn.offsetWidth; // reflow
            btn.classList.add('animate-wish');
            // Swap icon colour
            const icon = btn.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-heart',      true);
                icon.classList.toggle('text-red-500',  isWished);
                icon.classList.toggle('text-gray-300', !isWished);
            }
            window.showToast && window.showToast(
                isWished ? 'تمت الإضافة إلى المفضلة ❤️' : 'تمت إزالة المنتج من المفضلة',
                isWished ? 'success' : 'info'
            );
        };
    </script>
    <style>
        @keyframes wishPop {
            0%   { transform: scale(1); }
            35%  { transform: scale(1.45); }
            65%  { transform: scale(0.88); }
            85%  { transform: scale(1.12); }
            100% { transform: scale(1); }
        }
        .animate-wish { animation: wishPop 0.45s cubic-bezier(0.34,1.56,0.64,1); }
    </style>
    <script>
        // Smooth scroll to top function
        function scrollToTopSmooth() {
            var position = document.documentElement.scrollTop || document.body.scrollTop;
            if (position > 0) {
                window.requestAnimationFrame(scrollToTopSmooth);
                window.scrollTo(0, position - position / 8);
            }
        }

        // Show/hide back-to-top button on scroll
        (function() {
            var btn = document.getElementById('back-to-top');
            window.addEventListener('scroll', function() {
                if (window.scrollY > 320) {
                    btn.classList.add('visible');
                } else {
                    btn.classList.remove('visible');
                }
            }, { passive: true });
        })();

        // Scroll Spy Implementation — IntersectionObserver based
        document.addEventListener('DOMContentLoaded', function() {
            var navLinks = document.querySelectorAll('[data-scroll-spy]');
            if (!navLinks.length) return;

            // Map selector → links
            var linkMap = {};
            navLinks.forEach(function(link) {
                var sel = link.getAttribute('data-scroll-spy');
                if (!linkMap[sel]) linkMap[sel] = [];
                linkMap[sel].push(link);
            });

            // Track which sections are "visible"
            var visibleSections = new Set();

            // Ordered list of section selectors (top → bottom)
            var order = ['#', '#about', '#products-section', '#categories', '#blog', '#contact'];

            function setActive(activeSel) {
                order.forEach(function(sel) {
                    var links = linkMap[sel] || [];
                    links.forEach(function(link) {
                        var isDesktop = link.classList.contains('border-t-2');
                        if (sel === activeSel) {
                            link.classList.add('text-primary');
                            if (isDesktop) {
                                link.classList.remove('border-transparent');
                                link.classList.add('border-primary');
                            }
                        } else {
                            link.classList.remove('text-primary');
                            if (isDesktop) {
                                link.classList.remove('border-primary');
                                link.classList.add('border-transparent');
                            }
                        }
                    });
                });
            }

            function pickActive() {
                // Pick the topmost visible section
                for (var i = 0; i < order.length; i++) {
                    if (visibleSections.has(order[i])) {
                        setActive(order[i]);
                        return;
                    }
                }
                // Nothing visible — default to top (#)
                setActive('#');
            }

            // Observe each section
            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    var id = '#' + entry.target.id;
                    if (entry.isIntersecting) {
                        visibleSections.add(id);
                    } else {
                        visibleSections.delete(id);
                    }
                });
                pickActive();
            }, {
                rootMargin: '-56px 0px -40% 0px', // Account for sticky navbar height (56px = h-14)
                threshold: 0
            });

            order.forEach(function(sel) {
                if (sel === '#') return; // Home top — no dedicated element to observe
                var el = document.querySelector(sel);
                if (el) observer.observe(el);
            });

            // Mark home (#) visible when at very top
            window.addEventListener('scroll', function() {
                if (window.scrollY < 100) {
                    visibleSections.add('#');
                } else {
                    visibleSections.delete('#');
                }
                pickActive();
            }, { passive: true });

            // Init
            if (window.scrollY < 100) visibleSections.add('#');
            pickActive();
        });
    </script>

    <!-- Toastify JS & Notification Script -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        window.showToast = function(message, type = 'success') {
            let bgColor = '#2E8B57';
            if (type === 'error' || type === 'danger') bgColor = '#DC2626';
            else if (type === 'info') bgColor = '#D4A373';
            else if (type === 'warning') bgColor = '#F59E0B';
            
            Toastify({
                text: message,
                duration: 3000,
                newWindow: true,
                close: true,
                gravity: "top",
                position: "right",
                stopOnFocus: true,
                style: {
                    background: bgColor,
                    fontFamily: "'Cairo', sans-serif",
                    borderRadius: "12px",
                    fontWeight: "bold",
                    boxShadow: "0 4px 12px rgba(0,0,0,0.15)",
                }
            }).showToast();
        };

        /**
         * Global addToCart — used on product cards & related-products section.
         * @param {number} productId
         * @param {HTMLElement|null} btn  — the button element (for loading state)
         */
        window.addToCart = function(productId, btn) {
            // ------ Loading state ------
            const card = window._cartCards && window._cartCards[productId];
            if (card) {
                if (card.loading) return; // prevent double submit
                card.loading = true;
            }

            // Disable button + show spinner if btn passed
            if (btn) {
                if (btn._cartLoading) return;
                btn._cartLoading = true;
                btn._origHTML = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<svg class="animate-spin h-4 w-4 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>';
            }

            const resetBtn = () => {
                if (card) card.loading = false;
                if (btn) {
                    btn._cartLoading = false;
                    btn.disabled = false;
                    btn.innerHTML = btn._origHTML;
                }
            };

            fetch('{{ route('cart.add') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ product_id: productId, quantity: 1 })
            })
            .then(r => r.json())
            .then(data => {
                resetBtn();
                if (data.success) {
                    // Update navbar cart count
                    const body = document.querySelector('[x-data]');
                    if (body && body._x_dataStack && body._x_dataStack[0]) {
                        body._x_dataStack[0].cartCount = data.items_count;
                    }

                    // Update the product card quantity badge
                    if (card && data.item_quantity !== undefined) {
                        card.cartQty = data.item_quantity;
                    }

                    window.showToast(data.message || 'تمت إضافة المنتج إلى السلة! 🛒', 'success');
                } else {
                    window.showToast(data.message || 'حدث خطأ ما', 'error');
                }
            })
            .catch(() => {
                resetBtn();
                window.showToast('الرجاء المحاولة مرة أخرى.', 'error');
            });
        };

        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                showToast('{{ session('success') }}', 'success');
            @endif
            @if(session('error'))
                showToast('{{ session('error') }}', 'error');
            @endif
        });
    </script>
    @yield('scripts')
</body>
</html>