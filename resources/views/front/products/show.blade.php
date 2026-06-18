@extends('layouts.app')

@section('title', $product->name . ' - لحمكس')

@section('content')
<div class="py-10 bg-[#FAF7F4] min-h-screen"
     x-data="{
         quantity: 1,
         stock: {{ $product->stock_quantity }},
         activeImage: '{{ $product->image ? asset('storage/' . $product->image) : '' }}',
         showFullscreen: false,
         price: {{ $product->active_price }},
         optionsPrice: 0.00,
         deliveryOption: '',
         activeIdx: 0,
         addingToCart: false,
         isZoomed: false,
         images: Array.from(new Set([
             @if($product->image) '{{ asset('storage/' . $product->image) }}', @endif
             @if($product->images && is_array($product->images))
                 @foreach($product->images as $galImg)
                     '{{ asset('storage/' . $galImg) }}',
                 @endforeach
             @endif
         ])),
         prev() {
             if (this.images.length <= 1) return;
             this.activeIdx = (this.activeIdx === 0) ? this.images.length - 1 : this.activeIdx - 1;
             this.activeImage = this.images[this.activeIdx];
             this.scrollToThumb(this.activeIdx);
         },
         next() {
             if (this.images.length <= 1) return;
             this.activeIdx = (this.activeIdx === this.images.length - 1) ? 0 : this.activeIdx + 1;
             this.activeImage = this.images[this.activeIdx];
             this.scrollToThumb(this.activeIdx);
         },
         select(idx) {
             this.activeIdx = idx;
             this.activeImage = this.images[idx];
             this.scrollToThumb(idx);
         },
         scrollToThumb(idx) {
             const el = document.getElementById('thumb-' + idx);
             if (el) el.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
         },
         touchStartX: 0,
         handleSwipeStart(e) {
             this.touchStartX = e.clientX !== undefined ? e.clientX : (e.touches && e.touches[0] ? e.touches[0].clientX : 0);
         },
         handleSwipeEnd(e) {
             const endX = e.clientX !== undefined ? e.clientX : (e.changedTouches && e.changedTouches[0] ? e.changedTouches[0].clientX : 0);
             if (this.touchStartX === 0 || endX === 0) return;
             const diff = endX - this.touchStartX;
             if (diff > 50) { this.prev(); } else if (diff < -50) { this.next(); }
             this.touchStartX = 0;
         }
     }"
     x-init="activeIdx = 0; activeImage = images[0] || ''">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- MAIN PRODUCT CARD --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-8">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0;" class="product-grid">

                {{-- ===== COLUMN 1 (RIGHT in RTL): IMAGE GALLERY ===== --}}
                <div style="background: #faf7f4; padding: 1.5rem; display: flex; flex-direction: column;">

                    {{-- Main Image Container --}}
                    <div style="position: relative; background: white; border-radius: 16px; overflow: hidden; display: flex; align-items: center; justify-content: center; height: 420px; flex-shrink: 0;"
                         @mousedown="handleSwipeStart($event)"
                         @mouseup="handleSwipeEnd($event)"
                         @touchstart="handleSwipeStart($event)"
                         @touchend="handleSwipeEnd($event)">

                        {{-- Zoom Button --}}
                        <button type="button"
                                @click="showFullscreen = true"
                                @mouseenter="isZoomed = true"
                                @mouseleave="isZoomed = false"
                                style="position: absolute; top: 12px; right: 12px; z-index: 10; display: flex; align-items: center; gap: 6px; background: rgba(255,255,255,0.92); border: 1px solid #e5e7eb; color: #374151; font-size: 11px; font-weight: 700; padding: 7px 12px; border-radius: 999px; cursor: pointer; box-shadow: 0 1px 4px rgba(0,0,0,0.08); transition: background 0.2s, box-shadow 0.2s;"
                                onmouseover="this.style.background='rgba(255,255,255,1)'; this.style.boxShadow='0 3px 10px rgba(0,0,0,0.15)';"
                                onmouseout="this.style.background='rgba(255,255,255,0.92)'; this.style.boxShadow='0 1px 4px rgba(0,0,0,0.08)';">
                            🔍 تكبير الصورة
                        </button>

                        @if($product->discount_price)
                            <span style="position: absolute; top: 12px; left: 12px; z-index: 10; background: #7a0c0c; color: white; font-size: 11px; font-weight: 800; padding: 5px 12px; border-radius: 999px;">خصم مميز</span>
                        @endif

                        {{-- Main Image --}}
                        <template x-if="activeImage">
                            <img :src="activeImage" alt="{{ $product->name }}"
                                 :style="isZoomed
                                     ? 'max-width:100%; max-height:100%; object-fit:contain; cursor:zoom-in; transition:transform 0.35s cubic-bezier(0.34,1.56,0.64,1); transform:scale(1.08);'
                                     : 'max-width:100%; max-height:100%; object-fit:contain; cursor:zoom-in; transition:transform 0.35s cubic-bezier(0.34,1.56,0.64,1); transform:scale(1);'"
                                 @click="showFullscreen = true">
                        </template>
                        <template x-if="!activeImage">
                            <div style="font-size: 80px; display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;">🥩</div>
                        </template>

                        {{-- Arrows --}}
                        <template x-if="images.length > 1">
                            <div>
                                <button type="button" @click="prev()"
                                        style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); z-index: 10; width: 40px; height: 40px; background: white; border: 1px solid #e5e7eb; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #7a0c0c; cursor: pointer; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                    <i class="fa-solid fa-chevron-right" style="font-size: 13px;"></i>
                                </button>
                                <button type="button" @click="next()"
                                        style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); z-index: 10; width: 40px; height: 40px; background: white; border: 1px solid #e5e7eb; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #7a0c0c; cursor: pointer; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                    <i class="fa-solid fa-chevron-left" style="font-size: 13px;"></i>
                                </button>
                            </div>
                        </template>
                    </div>

                    {{-- Thumbnail Strip (slider when > 3 images) --}}
                    <div style="margin-top: 12px; position: relative;" x-data="{ thumbIdx: 0, thumbVisible: 3 }">

                        {{-- Prev Arrow (only if > 3 images) --}}
                        <template x-if="images.length > 3 && thumbIdx > 0">
                            <button type="button"
                                    @click="thumbIdx = Math.max(0, thumbIdx - 1)"
                                    style="position: absolute; right: -14px; top: 50%; transform: translateY(-50%); z-index: 10; width: 28px; height: 28px; background: white; border: 1px solid #e5e7eb; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #7a0c0c; cursor: pointer; box-shadow: 0 2px 6px rgba(0,0,0,0.12); transition: all 0.2s;"
                                    onmouseover="this.style.background='#7a0c0c'; this.style.color='white';"
                                    onmouseout="this.style.background='white'; this.style.color='#7a0c0c';">
                                <i class="fa-solid fa-chevron-right" style="font-size: 10px;"></i>
                            </button>
                        </template>

                        {{-- Next Arrow (only if > 3 images) --}}
                        <template x-if="images.length > 3 && thumbIdx < images.length - 3">
                            <button type="button"
                                    @click="thumbIdx = Math.min(images.length - 3, thumbIdx + 1)"
                                    style="position: absolute; left: -14px; top: 50%; transform: translateY(-50%); z-index: 10; width: 28px; height: 28px; background: white; border: 1px solid #e5e7eb; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #7a0c0c; cursor: pointer; box-shadow: 0 2px 6px rgba(0,0,0,0.12); transition: all 0.2s;"
                                    onmouseover="this.style.background='#7a0c0c'; this.style.color='white';"
                                    onmouseout="this.style.background='white'; this.style.color='#7a0c0c';">
                                <i class="fa-solid fa-chevron-left" style="font-size: 10px;"></i>
                            </button>
                        </template>

                        {{-- Slider Window --}}
                        <div style="overflow: hidden; width: 100%;">
                            <div :style="`display:flex; flex-wrap:nowrap; gap:8px; transition:transform 0.35s cubic-bezier(0.25,0.46,0.45,0.94); transform:translateX(${thumbIdx * -96}px);`"
                                 dir="ltr">
                                <template x-for="(img, idx) in images" :key="idx">
                                    <button type="button"
                                            :id="'thumb-' + idx"
                                            @click="select(idx)"
                                            :style="activeIdx === idx
                                                ? 'flex-shrink:0; width:80px; height:80px; border-radius:12px; overflow:hidden; display:flex; align-items:center; justify-content:center; background:white; cursor:pointer; transition:all 0.2s; border:2px solid #7a0c0c; transform:scale(0.95); box-shadow:0 2px 8px rgba(122,12,12,0.2);'
                                                : 'flex-shrink:0; width:80px; height:80px; border-radius:12px; overflow:hidden; display:flex; align-items:center; justify-content:center; background:white; cursor:pointer; transition:all 0.2s; border:2px solid #e5e7eb; opacity:0.6;'">
                                        <img :src="img" style="max-width:100%; max-height:100%; object-fit:contain; pointer-events:none;">
                                    </button>
                                </template>
                            </div>
                        </div>

                        {{-- Dots indicator (only when > 3) --}}
                        <template x-if="images.length > 3">
                            <div style="display: flex; justify-content: center; gap: 5px; margin-top: 8px;">
                                <template x-for="(img, idx) in images" :key="'dot-'+idx">
                                    <button type="button" @click="thumbIdx = Math.min(idx, images.length - 3); select(idx)"
                                            :style="activeIdx === idx
                                                ? 'width:18px; height:5px; border-radius:999px; background:#7a0c0c; border:none; cursor:pointer; transition:all 0.25s;'
                                                : 'width:5px; height:5px; border-radius:999px; background:#d1d5db; border:none; cursor:pointer; transition:all 0.25s;'">
                                    </button>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- ===== COLUMN 2 (LEFT in RTL): INFO PANEL ===== --}}
                <div style="padding: 2.5rem; border-right: 1px solid #f3f4f6; display: flex; flex-direction: column; justify-content: space-between;">

                    {{-- Top badges row --}}
                    <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 1rem;">
                        <span style="display: inline-flex; align-items: center; gap: 6px; background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; font-size: 11px; font-weight: 700; padding: 6px 12px; border-radius: 999px;">
                            🌿 منتج طازج
                        </span>
                        <button type="button" style="width: 36px; height: 36px; border-radius: 50%; border: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: center; color: #9ca3af; cursor: pointer; background: white;" 
                                onmouseover="this.style.color='#ef4444'; this.style.borderColor='#fca5a5';" 
                                onmouseout="this.style.color='#9ca3af'; this.style.borderColor='#e5e7eb';">
                            <i class="fa-regular fa-heart" style="font-size: 14px;"></i>
                        </button>
                    </div>

                    {{-- Category --}}
                    <span style="color: #b91c1c; font-weight: 800; font-size: 13px; display: block; margin-bottom: 4px;">{{ $product->category->name }}</span>

                    {{-- Product Name --}}
                    <h1 style="font-size: 2.8rem; font-weight: 900; color: #111827; margin-bottom: 10px; line-height: 1.2; font-family: 'Cairo', sans-serif;">
                        {{ $product->name }}
                    </h1>

                    {{-- Description --}}
                    @if($product->description)
                        <p style="color: #6b7280; font-size: 13px; line-height: 1.7; margin-bottom: 1.25rem;">{{ $product->description }}</p>
                    @endif

                    {{-- Feature Badges --}}
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px; margin-bottom: 1.25rem;">
                        <div style="background: #faf7f4; border: 1px solid #ede8e3; border-radius: 12px; padding: 12px 8px; text-align: center;">
                            <span style="font-size: 18px; display: block; margin-bottom: 4px;">🌿</span>
                            <span style="font-size: 10px; font-weight: 700; color: #374151; display: block; line-height: 1.3;">تربية طبيعية</span>
                            <span style="font-size: 9px; color: #9ca3af; display: block; margin-top: 2px;">بدون أعلاف صناعية</span>
                        </div>
                        <div style="background: #faf7f4; border: 1px solid #ede8e3; border-radius: 12px; padding: 12px 8px; text-align: center;">
                            <span style="font-size: 18px; display: block; margin-bottom: 4px;">✅</span>
                            <span style="font-size: 10px; font-weight: 700; color: #374151; display: block; line-height: 1.3;">ذبح شرعي</span>
                            <span style="font-size: 9px; color: #9ca3af; display: block; margin-top: 2px;">وفق الشريعة الإسلامية</span>
                        </div>
                        <div style="background: #faf7f4; border: 1px solid #ede8e3; border-radius: 12px; padding: 12px 8px; text-align: center;">
                            <span style="font-size: 18px; display: block; margin-bottom: 4px;">🔴</span>
                            <span style="font-size: 10px; font-weight: 700; color: #374151; display: block; line-height: 1.3;">طازج 100%</span>
                            <span style="font-size: 9px; color: #9ca3af; display: block; margin-top: 2px;">يوم الذبح والتوصيل</span>
                        </div>
                    </div>

                    {{-- Warning Notice --}}
                    <div style="background: #fff8f0; border: 1px solid #fddcb5; border-radius: 12px; padding: 10px 16px; display: flex; align-items: center; gap: 8px; margin-bottom: 1.25rem;">
                        <span style="color: #fb923c; font-size: 14px;">📅</span>
                        <span style="color: #c2410c; font-weight: 700; font-size: 12px;">لا يوجد شلوطة لرأس أيام العيد</span>
                    </div>

                    {{-- Pricing --}}
                    <div style="margin-bottom: 8px;">
                        @if($product->discount_price)
                            <div style="display: flex; align-items: baseline; gap: 12px; margin-bottom: 4px;">
                                <span style="font-size: 2.2rem; font-weight: 900; color: #7a0c0c;" x-text="(price * quantity).toFixed(2) + ' ر.س'"></span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                                <span style="font-size: 14px; color: #9ca3af; text-decoration: line-through;">{{ number_format($product->price, 2) }} ر.س</span>
                                <span style="background: #fee2e2; color: #7a0c0c; font-size: 10px; font-weight: 800; padding: 2px 8px; border-radius: 999px;">
                                    وفر {{ number_format($product->price - $product->discount_price, 2) }} ر.س
                                </span>
                            </div>
                        @else
                            <span style="font-size: 2.2rem; font-weight: 900; color: #7a0c0c;" x-text="(price * quantity).toFixed(2) + ' ر.س'"></span>
                        @endif

                        @if($product->weight)
                            <div style="display: flex; align-items: center; gap: 6px; margin-top: 6px;">
                                <span style="font-size: 13px;">⚖️</span>
                                <span style="font-size: 12px; font-weight: 600; color: #6b7280;">الوزن التقريبي: {{ $product->weight }} كجم</span>
                            </div>
                        @endif
                    </div>

                    @if($product->stock_quantity > 0)
                        {{-- Delivery --}}
                        <div style="margin-top: 16px; margin-bottom: 16px;">
                            <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 8px;">
                                <span style="color: #7a0c0c;">📍</span>
                                <span style="font-size: 13px; font-weight: 700; color: #374151;">التوصيل</span>
                            </div>
                            <div style="position: relative;">
                                <select x-model="deliveryOption"
                                        style="width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid #e5e7eb; font-size: 13px; font-weight: 600; color: #374151; background: white; appearance: none; text-align: right; cursor: pointer; outline: none;">
                                    <option value="">اختر يوم التوصيل...</option>
                                    <option value="شامل الذبح والتوصيل - أول يوم العيد">شامل الذبح والتوصيل - أول يوم العيد</option>
                                    <option value="شامل الذبح والتوصيل - ثاني يوم العيد">شامل الذبح والتوصيل - ثاني يوم العيد</option>
                                    <option value="شامل الذبح والتوصيل - ثالث يوم العيد">شامل الذبح والتوصيل - ثالث يوم العيد</option>
                                    <option value="شامل الذبح والتوصيل - رابع يوم العيد">شامل الذبح والتوصيل - رابع يوم العيد</option>
                                </select>
                                <div style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #9ca3af;">
                                    <i class="fa-solid fa-chevron-down" style="font-size: 11px;"></i>
                                </div>
                            </div>
                        </div>

                        {{-- Price Breakdown --}}
                        <div style="margin-bottom: 16px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                <span style="font-size: 13px; font-weight: 700; color: #111827;" x-text="(price * quantity).toFixed(2) + ' ر.س'"></span>
                                <span style="font-size: 13px; color: #6b7280; font-weight: 600;">سعر المنتج</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                <span style="font-size: 13px; font-weight: 700; color: #111827;" x-text="(optionsPrice * quantity).toFixed(2) + ' ر.س'"></span>
                                <span style="font-size: 13px; color: #6b7280; font-weight: 600;">خيارات إضافية</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 10px; border-top: 1px solid #f3f4f6;">
                                <span style="font-size: 1.4rem; font-weight: 900; color: #7a0c0c;" x-text="((price + optionsPrice) * quantity).toFixed(2) + ' ر.س'"></span>
                                <span style="font-size: 13px; font-weight: 700; color: #7a0c0c;">الإجمالي الكلي</span>
                            </div>
                        </div>

                        {{-- Quantity + Add to Cart --}}
                        <div style="display: flex; align-items: center; gap: 12px;">
                            {{-- Quantity --}}
                            <div style="display: flex; align-items: center; border: 2px solid #e5e7eb; border-radius: 12px; overflow: hidden; height: 52px; flex-shrink: 0;">
                                <button type="button" @click="if (quantity < stock) quantity++"
                                        style="width: 44px; height: 100%; display: flex; align-items: center; justify-content: center; background: #f9fafb; font-size: 20px; font-weight: 700; cursor: pointer; color: #374151; border: none; border-left: 2px solid #e5e7eb;">+</button>
                                <span style="width: 40px; height: 100%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 15px; color: #111827;"
                                      x-text="quantity"></span>
                                <button type="button" @click="if (quantity > 1) quantity--"
                                        style="width: 44px; height: 100%; display: flex; align-items: center; justify-content: center; background: #f9fafb; font-size: 20px; font-weight: 700; cursor: pointer; color: #374151; border: none; border-right: 2px solid #e5e7eb;">-</button>
                            </div>

                            {{-- Add to Cart Button with Loading State --}}
                            <button type="button"
                                    id="main-add-to-cart-btn"
                                    @click="addToCartWithOptions({{ $product->id }}, $el)"
                                    :disabled="addingToCart"
                                    :style="addingToCart
                                        ? 'flex:1; background:#a83232; color:white; font-weight:800; padding:14px 20px; border-radius:12px; border:none; cursor:not-allowed; font-size:14px; display:flex; align-items:center; justify-content:center; gap:8px; opacity:0.8; transition:background 0.2s;'
                                        : 'flex:1; background:#7a0c0c; color:white; font-weight:800; padding:14px 20px; border-radius:12px; border:none; cursor:pointer; font-size:14px; display:flex; align-items:center; justify-content:center; gap:8px; transition:background 0.2s;'"
                                    @mouseenter="if(!addingToCart) $el.style.background='#5f0909'"
                                    @mouseleave="if(!addingToCart) $el.style.background='#7a0c0c'">

                                {{-- Spinner (loading) --}}
                                <template x-if="addingToCart">
                                    <span style="display:flex; align-items:center; gap:8px;">
                                        <svg style="width:18px;height:18px;animation:spin 0.8s linear infinite;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle style="opacity:0.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path style="opacity:0.85" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                        </svg>
                                        جاري الإضافة...
                                    </span>
                                </template>

                                {{-- Normal state --}}
                                <template x-if="!addingToCart">
                                    <span>🛒 إضافة إلى السلة</span>
                                </template>
                            </button>
                        </div>

                    @else
                        <div style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; font-weight: 700; padding: 16px; border-radius: 12px; text-align: center; margin-top: 16px;">
                            ⚠️ نفذت الكمية من المخزون حالياً
                        </div>
                    @endif

                </div>

            </div>
        </div>

        {{-- BOTTOM FEATURES --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 2.5rem;">
            <div style="background: white; border-radius: 16px; padding: 20px; border: 1px solid #f3f4f6; display: flex; align-items: flex-start; gap: 14px; text-align: right;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #fef2f2; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 22px;">⏱️</div>
                <div>
                    <h3 style="font-weight: 800; color: #111827; margin-bottom: 6px; font-size: 14px;">توصيل سريع ومبرد</h3>
                    <p style="color: #6b7280; font-size: 11px; line-height: 1.6;">خدمة توصيل مبردة تضمن وصول الذبيحة طازجة وأفضل حال في الموعد المحدد</p>
                </div>
            </div>
            <div style="background: white; border-radius: 16px; padding: 20px; border: 1px solid #f3f4f6; display: flex; align-items: flex-start; gap: 14px; text-align: right;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #fef2f2; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 22px;">🔪</div>
                <div>
                    <h3 style="font-weight: 800; color: #111827; margin-bottom: 6px; font-size: 14px;">تقطيع حسب رغبتك</h3>
                    <p style="color: #6b7280; font-size: 11px; line-height: 1.6;">نوفر لك خدمة تقطيع الذبيحة حسب طلبك مجاناً وأعلى معايير النظافة</p>
                </div>
            </div>
            <div style="background: white; border-radius: 16px; padding: 20px; border: 1px solid #f3f4f6; display: flex; align-items: flex-start; gap: 14px; text-align: right;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #fef2f2; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 22px;">🌿</div>
                <div>
                    <h3 style="font-weight: 800; color: #111827; margin-bottom: 6px; font-size: 14px;">من مزارعنا إلى مائدتك</h3>
                    <p style="color: #6b7280; font-size: 11px; line-height: 1.6;">نعاج بلدي طازج يتم تربيتها في مزارع مختارة بعناية لتوفير أفضل جودة طعم وميزة غذائية</p>
                </div>
            </div>
        </div>

        {{-- Related Products --}}
        @if(!$relatedProducts->isEmpty())
            <div style="margin-bottom: 3.5rem; margin-top: 3.5rem;">
                <h2 style="font-size: 1.6rem; font-weight: 800; color: #111827; margin-bottom: 2rem; font-family: 'Cairo', sans-serif; text-align: right;">منتجات ذات صلة</h2>
                <div class="related-products-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px;">
                    @foreach($relatedProducts as $rel)
                        <div style="background: white; border-radius: 24px; overflow: hidden; border: 1px solid #e5e7eb; display: flex; flex-direction: column; justify-content: space-between; transition: all 0.3s; padding: 16px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);"
                             onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 10px 15px -3px rgba(0, 0, 0, 0.1)';" 
                             onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 6px -1px rgba(0, 0, 0, 0.05)';">
                            
                            {{-- Product Link Wrapper --}}
                            <a href="{{ route('products.show', $rel->slug) }}" style="text-decoration: none; display: block; flex-grow: 1;">
                                <div style="height: 280px; background: #faf7f4; border-radius: 16px; overflow: hidden; position: relative; display: flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                                    @if($rel->image)
                                        <img src="{{ asset('storage/' . $rel->image) }}" alt="{{ $rel->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <div style="font-size: 60px;">🥩</div>
                                    @endif
                                </div>
                                
                                <h3 style="font-weight: 800; color: #1f2937; font-size: 1.2rem; text-align: center; margin-bottom: 8px; font-family: 'Cairo', sans-serif; line-height: 1.4;">
                                    {{ $rel->name }}
                                </h3>
                            </a>
                            
                            {{-- Price & Add to Cart --}}
                            <div style="text-align: center; margin-top: auto; padding-top: 12px;">
                                <div style="font-size: 1.2rem; font-weight: 800; color: #b91c1c; margin-bottom: 14px; font-family: 'Cairo', sans-serif;">
                                    {{ number_format($rel->discount_price ?? $rel->price, 0) }} ر.س
                                </div>
                                
                                <button type="button"
                                        onclick="addToCartRelated({{ $rel->id }}, this)"
                                        style="width: 100%; background: #22c55e; color: white; font-weight: 800; font-size: 1rem; border: none; border-radius: 999px; cursor: pointer; padding: 12px 24px; transition: background 0.2s; font-family: 'Cairo', sans-serif; display: flex; align-items: center; justify-content: center; gap: 8px;"
                                        onmouseover="if(!this.disabled) this.style.background='#16a34a';"
                                        onmouseout="if(!this.disabled) this.style.background='#22c55e';">
                                    إضافة إلى السلة
                                </button>
                            </div>
                            
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>

{{-- Responsive styles --}}
<style>
    @media (max-width: 768px) {
        .product-grid {
            grid-template-columns: 1fr !important;
        }
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to   { transform: rotate(360deg); }
    }
</style>

{{-- FULLSCREEN LIGHTBOX --}}
<div x-show="showFullscreen"
     style="position: fixed; inset: 0; background: rgba(0,0,0,0.95); backdrop-filter: blur(8px); z-index: 9999; display: flex; flex-direction: column; justify-content: space-between; padding: 24px; user-select: none;"
     x-cloak
     @keydown.escape.window="showFullscreen = false"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">

    <div style="display: flex; justify-content: space-between; align-items: center; width: 100%; z-index: 10;">
        <span style="color: white; font-size: 13px; font-weight: 700; background: rgba(255,255,255,0.1); padding: 6px 16px; border-radius: 999px; border: 1px solid rgba(255,255,255,0.1);"
              x-text="(activeIdx + 1) + ' / ' + images.length"></span>
        <button @click="showFullscreen = false"
                style="background: rgba(255,255,255,0.1); color: white; border-radius: 50%; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; font-size: 18px; cursor: pointer; border: 1px solid rgba(255,255,255,0.1); font-weight: 700;">
            ✕
        </button>
    </div>

    <div style="flex: 1; display: flex; align-items: center; justify-content: center; position: relative; width: 100%; overflow: hidden;"
         @mousedown="handleSwipeStart($event)"
         @mouseup="handleSwipeEnd($event)"
         @touchstart="handleSwipeStart($event)"
         @touchend="handleSwipeEnd($event)">

        <template x-if="images.length > 1">
            <button type="button" @click.stop="prev()"
                    style="position: absolute; right: 16px; background: rgba(0,0,0,0.6); color: white; padding: 16px; border-radius: 50%; border: 1px solid rgba(255,255,255,0.2); cursor: pointer; z-index: 10; display: flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-chevron-right" style="font-size: 18px;"></i>
            </button>
        </template>

        <div style="max-width: 85vw; height: 65vh; display: flex; align-items: center; justify-content: center; pointer-events: none;">
            <img :src="activeImage" style="max-width: 100%; max-height: 100%; object-fit: contain; border-radius: 12px;">
        </div>

        <template x-if="images.length > 1">
            <button type="button" @click.stop="next()"
                    style="position: absolute; left: 16px; background: rgba(0,0,0,0.6); color: white; padding: 16px; border-radius: 50%; border: 1px solid rgba(255,255,255,0.2); cursor: pointer; z-index: 10; display: flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-chevron-left" style="font-size: 18px;"></i>
            </button>
        </template>
    </div>

    <div style="display: flex; justify-content: center; padding-bottom: 8px; z-index: 10;">
        <div style="display: flex; align-items: center; gap: 8px; overflow-x: auto; max-width: 85vw; padding: 8px 0;">
            <template x-for="(img, idx) in images" :key="idx">
                <button type="button"
                        @click="select(idx)"
                        style="width: 56px; height: 56px; border-radius: 10px; overflow: hidden; display: flex; align-items: center; justify-content: center; flex-shrink: 0; cursor: pointer; background: rgba(255,255,255,0.1); transition: all 0.2s;"
                        :style="activeIdx === idx ? 'border: 2px solid #7a0c0c; transform: scale(0.92);' : 'border: 2px solid rgba(255,255,255,0.2); opacity: 0.5;'">
                    <img :src="img" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                </button>
            </template>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // ─── Add to Cart (main product page) — with loading guard ───────────────
    function addToCartWithOptions(productId, btn) {
        const el = document.querySelector('[x-data]');
        if (!el) return;

        const alpineData = (el._x_dataStack && el._x_dataStack[0]) || (el.__x && el.__x.$data) || { quantity: 1, deliveryOption: '' };

        if (!alpineData.deliveryOption) {
            Swal.fire({
                icon: 'warning',
                title: 'تنبيه',
                text: 'الرجاء اختيار خيار التوصيل أولاً.',
                confirmButtonText: 'حسناً',
                confirmButtonColor: '#7A0C0C'
            });
            return;
        }

        // Prevent double submit
        if (alpineData.addingToCart) return;
        alpineData.addingToCart = true;

        fetch('{{ route('cart.add') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: alpineData.quantity,
                options: 'التوصيل: ' + alpineData.deliveryOption
            })
        })
        .then(response => response.json())
        .then(data => {
            alpineData.addingToCart = false;
            if (data.success) {
                // Update navbar cart count
                const body = document.querySelector('body');
                if (body && body._x_dataStack && body._x_dataStack[0]) {
                    body._x_dataStack[0].cartCount = data.items_count;
                }
                Swal.fire({
                    icon: 'success',
                    title: 'نجاح',
                    text: data.message,
                    confirmButtonText: 'إكمال التسوق',
                    showCancelButton: true,
                    cancelButtonText: 'ذهاب إلى السلة 🛒',
                    confirmButtonColor: '#7A0C0C',
                    cancelButtonColor: '#2E8B57'
                }).then((result) => {
                    if (result.isDismissed) {
                        window.location.href = '{{ route('cart.index') }}';
                    }
                });
            } else {
                Swal.fire({ icon: 'error', title: 'خطأ', text: data.message || 'حدث خطأ ما', confirmButtonColor: '#7A0C0C' });
            }
        })
        .catch(() => {
            alpineData.addingToCart = false;
            Swal.fire({ icon: 'error', title: 'خطأ', text: 'الرجاء المحاولة مرة أخرى.', confirmButtonColor: '#7A0C0C' });
        });
    }

    // ─── Add to Cart for Related Products — with loading guard ───────────────
    function addToCartRelated(productId, btn) {
        if (!btn || btn._cartLoading) return;
        btn._cartLoading = true;
        btn._origHTML = btn.innerHTML;
        btn.disabled = true;
        btn.style.opacity = '0.75';
        btn.style.cursor = 'not-allowed';
        btn.innerHTML = '<svg style="width:18px;height:18px;animation:spin 0.8s linear infinite;display:inline-block;vertical-align:middle" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle style="opacity:.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path style="opacity:.85" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>';

        const reset = () => {
            btn._cartLoading = false;
            btn.disabled = false;
            btn.style.opacity = '1';
            btn.style.cursor = 'pointer';
            btn.innerHTML = btn._origHTML;
        };

        fetch('{{ route('cart.add') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ product_id: productId, quantity: 1 })
        })
        .then(r => r.json())
        .then(data => {
            reset();
            if (data.success) {
                const body = document.querySelector('body');
                if (body && body._x_dataStack && body._x_dataStack[0]) {
                    body._x_dataStack[0].cartCount = data.items_count;
                }
                if (window.showToast) window.showToast(data.message || 'تمت الإضافة إلى السلة! 🛒', 'success');
            } else {
                if (window.showToast) window.showToast(data.message || 'حدث خطأ ما', 'error');
            }
        })
        .catch(() => { reset(); if (window.showToast) window.showToast('الرجاء المحاولة مرة أخرى.', 'error'); });
    }

    function addToCart(productId) {
        fetch('{{ route('cart.add') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update cart count globally in Alpine.js
                const AlpineBody = document.querySelector('body');
                if (AlpineBody) {
                    const alpineData = (AlpineBody._x_dataStack && AlpineBody._x_dataStack[0]) || (AlpineBody.__x && AlpineBody.__x.$data);
                    if (alpineData) {
                        alpineData.cartCount = data.items_count;
                    }
                }
                
                // Show a beautiful toast notification
                if (window.showToast) {
                    window.showToast(data.message || 'تمت إضافة المنتج إلى السلة! 🛒', 'success');
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'نجاح',
                        text: data.message || 'تمت إضافة المنتج إلى السلة!',
                        confirmButtonColor: '#7A0C0C',
                        timer: 2000
                    });
                }
            } else {
                if (window.showToast) {
                    window.showToast(data.message || 'حدث خطأ ما', 'error');
                } else {
                    Swal.fire({ icon: 'error', title: 'خطأ', text: data.message || 'حدث خطأ ما', confirmButtonColor: '#7A0C0C' });
                }
            }
        })
        .catch(() => {
            if (window.showToast) {
                window.showToast('الرجاء المحاولة مرة أخرى.', 'error');
            } else {
                Swal.fire({ icon: 'error', title: 'خطأ', text: 'الرجاء المحاولة مرة أخرى.', confirmButtonColor: '#7A0C0C' });
            }
        });
    }
</script>
@endsection
