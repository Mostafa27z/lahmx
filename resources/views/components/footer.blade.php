<!-- Footer -->
<footer class="text-white pt-16 pb-8 relative overflow-hidden" 
        style="border-top: 4px solid var(--accent);
               background-image: url('https://res.cloudinary.com/dmma4cjad/image/upload/v1781778317/9f553252-d1ba-4c91-87ee-90c72bbff2f7_wfyhr2.png');
               background-size: cover;
               background-position: center;
               background-repeat: no-repeat;">
    <!-- Dark overlay for readability -->
    <div class="absolute inset-0 pointer-events-none" style="background: linear-gradient(135deg, rgba(26,2,2,0.82) 0%, rgba(26,2,2,0.78) 60%, rgba(26,2,2,0.70) 100%);"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Top Features Bar (Inspired by the banner) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pb-12 mb-12 border-b border-white/10 text-center">
            <!-- Feature 1: طازجة 100% -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 p-5 rounded-2xl bg-white/8 border border-white/10 hover:border-accent/50 hover:bg-white/12 transition-all duration-300">
                <div class="w-12 h-12 rounded-full border-2 border-accent flex items-center justify-center text-accent text-xl shrink-0">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div class="text-right">
                    <h4 class="font-bold text-accent text-base">طازجة 100%</h4>
                    <p class="text-xs text-white/75 mt-1">ذبائح بلدية طازجة تذبح يومياً</p>
                </div>
            </div>

            <!-- Feature 2: مبردة للحفاظ على الجودة -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 p-5 rounded-2xl bg-white/8 border border-white/10 hover:border-accent/50 hover:bg-white/12 transition-all duration-300">
                <div class="w-12 h-12 rounded-full border-2 border-accent flex items-center justify-center text-accent text-xl shrink-0">
                    <i class="fa-solid fa-snowflake"></i>
                </div>
                <div class="text-right">
                    <h4 class="font-bold text-accent text-base">مبردة للحفاظ على الجودة</h4>
                    <p class="text-xs text-white/75 mt-1">شحن وتوصيل مبرد متكامل</p>
                </div>
            </div>

            <!-- Feature 3: جودة مضمونة -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 p-5 rounded-2xl bg-white/8 border border-white/10 hover:border-accent/50 hover:bg-white/12 transition-all duration-300">
                <div class="w-12 h-12 rounded-full border-2 border-accent flex items-center justify-center text-accent text-xl shrink-0">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div class="text-right">
                    <h4 class="font-bold text-accent text-base">جودة مضمونة</h4>
                    <p class="text-xs text-white/75 mt-1">رعاية بيطرية وإشراف كامل</p>
                </div>
            </div>
        </div>

        <!-- Main Footer Content Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 pb-12">
            <!-- Brand Info & Slogan & Socials -->
            <div class="flex flex-col items-center md:items-start text-center md:text-right">
                <div class="mb-4">
                    <img src="https://res.cloudinary.com/dmma4cjad/image/upload/v1781767352/1b04de4f-92ea-43ce-812a-3fe180240ab8_hieejs.png" alt="لحمكس - Lahmix" class="h-20 w-auto object-contain" style="filter: brightness(1.15) drop-shadow(0 2px 12px rgba(212,163,115,0.35));">
                </div>
                <p class="text-sm leading-relaxed mb-6 max-w-sm text-white/90">
                    لحوم طازجة، توصيل سريع وآمن حتى باب بيتك. خيارك الأمثل للحصول على أجود أنواع اللحوم المنتقاة بعناية فائقة.
                </p>
                
                <!-- Social Media Icons (RTL flow) -->
                <div class="flex items-center gap-3">
                    <!-- WhatsApp -->
                    @if($whatsapp = \App\Models\Setting::get('company_whatsapp'))
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsapp) }}" target="_blank" rel="noopener noreferrer"
                       class="w-10 h-10 rounded-full text-white flex items-center justify-center transition-all duration-200 hover:scale-110 shadow-md"
                       style="background-color: #25D366;"
                       aria-label="WhatsApp">
                        <i class="fa-brands fa-whatsapp text-lg"></i>
                    </a>
                    @endif
                    <!-- Snapchat -->
                    @if($snapchat = \App\Models\Setting::get('social_snapchat'))
                    <a href="{{ $snapchat }}" target="_blank" rel="noopener noreferrer"
                       class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-200 hover:scale-110 shadow-md"
                       style="background-color: #FFFC00; color: #000;"
                       aria-label="Snapchat">
                        <i class="fa-brands fa-snapchat text-lg"></i>
                    </a>
                    @endif
                    <!-- TikTok -->
                    @if($tiktok = \App\Models\Setting::get('social_tiktok'))
                    <a href="{{ $tiktok }}" target="_blank" rel="noopener noreferrer"
                       class="w-10 h-10 rounded-full text-white flex items-center justify-center transition-all duration-200 hover:scale-110 shadow-md"
                       style="background-color: #010101;"
                       aria-label="TikTok">
                        <i class="fa-brands fa-tiktok text-lg"></i>
                    </a>
                    @endif
                    <!-- Instagram -->
                    @if($instagram = \App\Models\Setting::get('social_instagram'))
                    <a href="{{ $instagram }}" target="_blank" rel="noopener noreferrer"
                       class="w-10 h-10 rounded-full text-white flex items-center justify-center transition-all duration-200 hover:scale-110 shadow-md"
                       style="background: radial-gradient(circle at 30% 110%, #f09433, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888);"
                       aria-label="Instagram">
                        <i class="fa-brands fa-instagram text-lg"></i>
                    </a>
                    @endif
                    <!-- Facebook -->
                    @if($facebook = \App\Models\Setting::get('social_facebook'))
                    <a href="{{ $facebook }}" target="_blank" rel="noopener noreferrer"
                       class="w-10 h-10 rounded-full text-white flex items-center justify-center transition-all duration-200 hover:scale-110 shadow-md"
                       style="background-color: #1877F2;"
                       aria-label="Facebook">
                        <i class="fa-brands fa-facebook-f text-lg"></i>
                    </a>
                    @endif
                </div>
            </div>

            <!-- Quick Links -->
            <div class="text-center md:text-right">
                <h3 class="text-lg font-bold mb-6 text-accent border-r-4 pr-3 border-accent">روابط سريعة</h3>
                <ul class="space-y-3 text-sm text-white/90">
                    <li>
                        <a href="{{ route('home') }}" class="hover:text-white transition flex items-center justify-center md:justify-start gap-2">
                            <span class="text-accent text-[9px]">◀</span> الرئيسية
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('products.index') }}" class="hover:text-white transition flex items-center justify-center md:justify-start gap-2">
                            <span class="text-accent text-[9px]">◀</span> منتجاتنا
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('about') }}" class="hover:text-white transition flex items-center justify-center md:justify-start gap-2">
                            <span class="text-accent text-[9px]">◀</span> من نحن
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('home') }}#categories" class="hover:text-white transition flex items-center justify-center md:justify-start gap-2">
                            <span class="text-accent text-[9px]">◀</span> المواشي
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('home') }}#contact" class="hover:text-white transition flex items-center justify-center md:justify-start gap-2">
                            <span class="text-accent text-[9px]">◀</span> تواصل معنا
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Contact Info (RTL) -->
            <div class="text-center md:text-right">
                <h3 class="text-lg font-bold mb-6 text-accent border-r-4 pr-3 border-accent">تواصل معنا</h3>
                <ul class="space-y-4 text-sm text-white/90">
                    @if($address = \App\Models\Setting::get('company_address'))
                    <li class="flex items-center justify-center md:justify-start gap-3">
                        <span class="w-8 h-8 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-accent shrink-0">
                            <i class="fa-solid fa-location-dot"></i>
                        </span>
                        <span>{{ $address }}</span>
                    </li>
                    @endif
                    @if($phone = \App\Models\Setting::get('company_phone'))
                    <li class="flex items-center justify-center md:justify-start gap-3">
                        <span class="w-8 h-8 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-accent shrink-0">
                            <i class="fa-solid fa-phone"></i>
                        </span>
                        <span dir="ltr">{{ $phone }}</span>
                    </li>
                    @endif
                    @if($whatsapp = \App\Models\Setting::get('company_whatsapp'))
                    <li class="flex items-center justify-center md:justify-start gap-3">
                        <span class="w-8 h-8 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-accent shrink-0">
                            <i class="fa-brands fa-whatsapp"></i>
                        </span>
                        <span dir="ltr">{{ $whatsapp }}</span>
                    </li>
                    @endif
                    @if($email = \App\Models\Setting::get('company_email'))
                    <li class="flex items-center justify-center md:justify-start gap-3">
                        <span class="w-8 h-8 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-accent shrink-0">
                            <i class="fa-solid fa-envelope"></i>
                        </span>
                        <span>{{ $email }}</span>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
        
        <!-- Bottom copyright -->
        <div class="mt-8 pt-6 border-t border-white/15 text-center text-xs text-white/80">
            &copy; {{ date('Y') }} لحمكس للذبائح الطازجة. جميع الحقوق محفوظة.
        </div>
    </div>
</footer>
