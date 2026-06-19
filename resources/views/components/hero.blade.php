<!-- Hero Banner Carousel Component -->
<div class="relative w-full overflow-hidden select-none bg-red-950 border-b-8 border-accent"
     x-data="{
        activeSlide: 0,
        slides: [
            {
                desktop: 'https://res.cloudinary.com/dmma4cjad/image/upload/v1781774705/8e66a3cc-ba61-4a6f-82b1-f4cd0b924425_uolb4c.png',
                mobile: 'https://res.cloudinary.com/dmma4cjad/image/upload/v1781767360/4d7d03eb-7e61-4ee8-82ea-63fba2b3a392_fvdp8c.png'
            },
            {
                desktop: 'https://res.cloudinary.com/dmma4cjad/image/upload/v1781775169/880263c4-3b03-4e3f-a506-8f78b57966b4_g32xtl.png',
                mobile: 'https://res.cloudinary.com/dmma4cjad/image/upload/v1781771168/7dd9043b-1764-415d-b9cc-cd44355d0808_vh5lr4.png'
            },
            {
                desktop: 'https://res.cloudinary.com/dmma4cjad/image/upload/v1781774735/079a4d58-0dfa-4f5a-97a1-31c2e37fe311_s0n6oh.png',
                mobile: 'https://res.cloudinary.com/dmma4cjad/image/upload/v1781771001/1dabc47f-780c-4e1e-89ca-98fc3b8173af_dve2tc.png'
            },
            {
                desktop: 'https://res.cloudinary.com/dmma4cjad/image/upload/v1781774979/0fee9bcd-2e5d-4366-a94a-7cce45d1d659_l4yqmu.png',
                mobile: 'https://res.cloudinary.com/dmma4cjad/image/upload/v1781767360/4d7d03eb-7e61-4ee8-82ea-63fba2b3a392_fvdp8c.png'
            }
        ],
        timer: null,
        startAutoPlay() {
            this.timer = setInterval(() => {
                this.next();
            }, 2000);
        },
        stopAutoPlay() {
            if (this.timer) {
                clearInterval(this.timer);
                this.timer = null;
            }
        },
        next() {
            this.activeSlide = (this.activeSlide + 1) % this.slides.length;
        },
        prev() {
            this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length;
        },
        init() {
            this.startAutoPlay();
        }
     }"
     @mouseenter="stopAutoPlay()"
     @mouseleave="startAutoPlay()"
     class="relative group">

    <!-- Slides Wrapper -->
    <div class="relative w-full overflow-hidden">
        <!-- LTR flex container guarantees sliding behaves correctly -->
        <div dir="ltr" class="flex w-full transition-transform duration-700 ease-in-out" 
             :style="`transform: translateX(-${activeSlide * 100}%);`">
            
            <template x-for="(slide, index) in slides" :key="index">
                <div class="w-full flex-shrink-0 relative">
                    <!-- Desktop Banner -->
                    <img :src="slide.desktop" alt="Lahmix Banner Desktop" class="w-full h-auto hidden md:block">
                    <!-- Mobile/Tablet Banner -->
                    <img :src="slide.mobile" alt="Lahmix Banner Mobile" class="w-full h-auto block md:hidden">
                    <!-- Subtle overlay -->
                    <div class="absolute inset-0 bg-black/5"></div>
                </div>
            </template>
        </div>
    </div>

    <!-- Navigation Arrows (Always visible and responsive) -->
    <div>
        <!-- Prev Slide -->
        <button @click="prev()" 
                class="absolute left-2 sm:left-4 top-1/2 -translate-y-1/2 w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-black/45 hover:bg-primary text-white flex items-center justify-center border border-white/10 transition-all duration-200 cursor-pointer shadow-md hover:scale-105 active:scale-95 z-20" 
                aria-label="Previous Slide">
            <svg class="w-3.5 h-3.5 sm:w-5 sm:h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>

        <!-- Next Slide -->
        <button @click="next()" 
                class="absolute right-2 sm:right-4 top-1/2 -translate-y-1/2 w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-black/45 hover:bg-primary text-white flex items-center justify-center border border-white/10 transition-all duration-200 cursor-pointer shadow-md hover:scale-105 active:scale-95 z-20" 
                aria-label="Next Slide">
            <svg class="w-3.5 h-3.5 sm:w-5 sm:h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
    </div>

    <!-- Pagination Dots -->
    <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex items-center gap-1.5 z-10">
        <template x-for="(slide, index) in slides" :key="index">
            <button @click="activeSlide = index" 
                    class="h-1.5 rounded-full transition-all duration-500 cursor-pointer"
                    :class="activeSlide === index ? 'w-5 bg-accent shadow-sm' : 'w-1.5 bg-white/50 hover:bg-white/80'"
                    :aria-label="`Go to slide ${index + 1}`">
            </button>
        </template>
    </div>
</div>
