<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'تسجيل الدخول - لحمكس')</title>

    <!-- Google Fonts: Cairo -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS & Vite -->
    @vite(['resources/css/app.css'])

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }
    </style>
</head>

<body class="min-h-screen bg-cover bg-center bg-no-repeat flex items-center justify-center p-4 sm:p-6"
    style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://res.cloudinary.com/dmma4cjad/image/upload/v1781820915/4f412083-3446-40b4-9ea8-6d2befc96733_pepmne.png');">

    <div
        class="max-w-xl w-full bg-[#FAF9F6] rounded-[32px] shadow-2xl p-6 sm:p-10 relative overflow-hidden border border-white/20">
        <!-- Back to site link -->
        <a href="{{ route('home') }}"
            class="absolute top-6 right-6 sm:top-10 sm:right-10 flex items-center gap-2 text-gray-500 hover:text-gray-800 transition duration-150 text-sm font-bold select-none cursor-pointer">
            <span>العودة إلى الموقع</span>
            <i class="fa-solid fa-arrow-left text-xs"></i>
        </a>

        <div class="w-full flex flex-col items-center justify-center mt-6">
            <!-- Logo Branding -->
            <a href="{{ route('home') }}" class="mb-4 transition-transform duration-200 hover:scale-105">
                <img src="https://res.cloudinary.com/dmma4cjad/image/upload/v1781767352/1b04de4f-92ea-43ce-812a-3fe180240ab8_hieejs.png"
                    alt="لحمكس - Lahmix" class="h-16 w-auto object-contain">
            </a>

            @yield('content')
        </div>
    </div>

    <!-- Toastify JS & Alert Script -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        window.showToast = function (message, type = 'success') {
            let bgColor = '#7A0C0C';
            if (type === 'error' || type === 'danger') bgColor = '#DC2626';

            Toastify({
                text: message,
                duration: 3000,
                newWindow: true,
                close: true,
                gravity: "top",
                position: "right",
                style: {
                    background: bgColor,
                    fontFamily: "'Cairo', sans-serif",
                    borderRadius: "12px",
                    fontWeight: "bold",
                }
            }).showToast();
        }

        document.addEventListener('DOMContentLoaded', function () {
            @if(session('success'))
                showToast('{{ session('success') }}', 'success');
            @endif
            @if(session('error'))
                showToast('{{ session('error') }}', 'error');
            @endif
        });
    </script>
</body>

</html>