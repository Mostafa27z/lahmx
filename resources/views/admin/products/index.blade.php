@extends('layouts.admin')

@section('title', 'إدارة المنتجات - لوحة التحكم')

@section('content')
<div class="space-y-6" x-data="{ 
    addModalOpen: false, 
    editModalOpen: false,
    editProduct: { 
        id: null, 
        name: '', 
        category_id: '', 
        price: '', 
        discount_price: '', 
        stock_quantity: 10, 
        weight: '', 
        description: '', 
        is_available: true, 
        imageUrl: '',
        galleryUrls: [],
        galleryPaths: [],
        pathsToDelete: []
    },
    openEditModal(id, name, categoryId, price, discountPrice, stockQuantity, weight, description, isAvailable, imageUrl, galleryUrls, galleryPaths) {
        this.editProduct = { 
            id: id, 
            name: name, 
            category_id: categoryId, 
            price: price, 
            discount_price: discountPrice, 
            stock_quantity: stockQuantity, 
            weight: weight, 
            description: description, 
            is_available: isAvailable, 
            imageUrl: imageUrl,
            galleryUrls: galleryUrls,
            galleryPaths: galleryPaths ? [...galleryPaths] : [],
            pathsToDelete: []
        };
        this.editModalOpen = true;
    },
    removeExistingGalleryImage(index) {
        this.editProduct.pathsToDelete.push(this.editProduct.galleryPaths[index]);
        this.editProduct.galleryUrls.splice(index, 1);
        this.editProduct.galleryPaths.splice(index, 1);
    }
}">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">إدارة المنتجات 🥩</h1>
            <p class="text-gray-500 mt-1">عرض وتعديل وإضافة منتجات اللحوم والمواشي</p>
        </div>
        <button @click="addModalOpen = true" class="bg-primary hover:bg-primary-dark text-white font-bold px-6 py-3 rounded-lg transition shadow cursor-pointer">
            إضافة منتج جديد +
        </button>
    </div>

    <!-- Error/Validation alert -->
    @if($errors->any())
        <div class="bg-red-50 border-r-4 border-red-500 p-4 rounded-xl">
            <div class="flex">
                <div class="flex-shrink-0">
                    <span class="text-red-500 text-lg">⚠️</span>
                </div>
                <div class="mr-3">
                    <h3 class="text-sm font-bold text-red-800">يرجى تصحيح الأخطاء التالية:</h3>
                    <ul class="mt-2 list-disc list-inside text-xs text-red-700 font-semibold space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-right border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 font-bold border-b border-gray-100">
                    <th class="p-6">الصورة</th>
                    <th class="p-6">المنتج</th>
                    <th class="p-6">الأقسام</th>
                    <th class="p-6">السعر</th>
                    <th class="p-6">المخزون</th>
                    <th class="p-6">الحالة</th>
                    <th class="p-6 text-left">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 font-semibold text-gray-700">
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50/50">
                        <td class="p-6">
                            <div class="w-12 h-12 rounded-xl bg-gray-50 border border-gray-100 overflow-hidden flex items-center justify-center text-xl">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="" class="w-full h-full object-cover">
                                @else
                                    🥩
                                @endif
                            </div>
                        </td>
                        <td class="p-6">
                            <span class="font-bold text-gray-800 block">{{ $product->name }}</span>
                            <span class="text-xs text-gray-400 font-normal block mt-0.5">{{ $product->slug }}</span>
                        </td>
                        <td class="p-6 text-sm text-gray-500">
                            {{ $product->category->name ?? 'غير محدد' }}
                        </td>
                        <td class="p-6 text-primary font-bold">
                            @if($product->discount_price)
                                <span class="text-gray-400 line-through text-xs font-normal ml-1.5">{{ number_format($product->price, 2) }} ر.س</span>
                                <span>{{ number_format($product->discount_price, 2) }} ر.س</span>
                            @else
                                <span>{{ number_format($product->price, 2) }} ر.س</span>
                            @endif
                        </td>
                        <td class="p-6">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold {{ $product->stock_quantity > 5 ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800' }}">
                                {{ $product->stock_quantity }} وحدة
                            </span>
                        </td>
                        <td class="p-6">
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold {{ $product->is_available && $product->stock_quantity > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $product->is_available && $product->stock_quantity > 0 ? 'متوفر' : 'غير متوفر' }}
                            </span>
                        </td>
                        <td class="p-6 text-left flex items-center justify-end gap-3">
                            <button @click="openEditModal(
                                        {{ $product->id }}, 
                                        '{{ addslashes($product->name) }}', 
                                        '{{ $product->category_id }}', 
                                        '{{ $product->price }}', 
                                        '{{ $product->discount_price }}', 
                                        {{ $product->stock_quantity }}, 
                                        '{{ $product->weight }}', 
                                        '{{ addslashes($product->description ?? '') }}', 
                                        {{ $product->is_available ? 'true' : 'false' }}, 
                                        '{{ $product->image ? asset('storage/' . $product->image) : '' }}',
                                        {{ json_encode(array_map(fn($img) => asset('storage/' . $img), $product->images ?? [])) }},
                                        {{ json_encode($product->images ?? []) }}
                                    )" 
                                    class="text-blue-600 hover:text-blue-800 font-bold transition cursor-pointer">تعديل 📝</button>
                            <form id="delete-form-{{ $product->id }}" action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete({{ $product->id }})" class="text-red-600 hover:text-red-800 transition cursor-pointer">حذف 🗑️</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-12 text-center text-gray-400 font-bold">لا توجد منتجات حالياً.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="p-6 border-t border-gray-50">
            {{ $products->links() }}
        </div>
    </div>

    <!-- ADD PRODUCT DIALOG (MODAL) -->
    <div x-show="addModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto" style="display: none;">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity" @click="addModalOpen = false"></div>

        <!-- Modal Content -->
        <div class="relative transform overflow-hidden rounded-2xl bg-white p-8 text-right shadow-2xl transition-all w-full max-w-2xl border border-gray-100/50 z-10 my-8 max-h-[90vh] flex flex-col"
             x-show="addModalOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4">
            
            <!-- Close Button -->
            <button @click="addModalOpen = false" class="absolute top-4 left-4 text-gray-400 hover:text-gray-600 focus:outline-none cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <!-- Modal Title -->
            <div class="mb-6 flex items-center gap-2 flex-shrink-0">
                <span class="text-2xl">🥩</span>
                <div>
                    <h3 class="text-xl font-extrabold text-gray-900">إضافة منتج جديد</h3>
                    <p class="text-gray-400 text-xs mt-0.5">أدخل تفاصيل ومواصفات منتج اللحوم الجديد</p>
                </div>
            </div>

            <!-- Scrollable Form Body -->
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5 overflow-y-auto flex-grow pr-1">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-4">
                    <!-- Name (Floating Label) -->
                    <div class="relative w-full" x-data="{ focus: false, filled: false }">
                        <input type="text" name="name" id="name" required value="{{ old('name') }}"
                               @focus="focus = true"
                               @blur="focus = false; filled = ($el.value.length > 0)"
                               @input="filled = ($el.value.length > 0)"
                               class="w-full text-right bg-white text-gray-800 px-4 py-4 rounded-xl border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none placeholder-transparent text-sm font-semibold transition-all duration-200"
                               placeholder=" ">
                        <label for="name" 
                               class="absolute right-4 text-gray-400 transition-all duration-300 pointer-events-none origin-top-right bg-white px-1.5"
                               :class="focus || filled ? 'top-0 -translate-y-1/2 scale-75 font-bold text-primary' : 'top-1/2 -translate-y-1/2 scale-100 text-gray-400'"
                               style="transform-origin: top right;">
                            اسم المنتج/العنوان (العربية)
                        </label>
                    </div>

                    <!-- Category (Floating Label) -->
                    <div class="relative w-full" x-data="{ focus: false, filled: false }">
                        <select name="category_id" id="category_id" required
                                @focus="focus = true"
                                @blur="focus = false; filled = ($el.value.length > 0)"
                                @change="filled = ($el.value.length > 0)"
                                class="w-full text-right bg-white text-gray-800 px-4 py-4 rounded-xl border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none placeholder-transparent text-sm font-semibold transition-all duration-200 appearance-none">
                            <option value=""></option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <label for="category_id" 
                               class="absolute right-4 text-gray-400 transition-all duration-300 pointer-events-none origin-top-right bg-white px-1.5"
                               :class="focus || filled || '{{ old('category_id') }}' ? 'top-0 -translate-y-1/2 scale-75 font-bold text-primary' : 'top-1/2 -translate-y-1/2 scale-100 text-gray-400'"
                               style="transform-origin: top right;">
                            التصنيف (القسم)
                        </label>
                    </div>

                    <!-- Price (Floating Label) -->
                    <div class="relative w-full" x-data="{ focus: false, filled: false }">
                        <input type="number" name="price" id="price" required step="0.01" min="0" value="{{ old('price') }}"
                               @focus="focus = true"
                               @blur="focus = false; filled = ($el.value.length > 0)"
                               @input="filled = ($el.value.length > 0)"
                               class="w-full text-right bg-white text-gray-800 px-4 py-4 rounded-xl border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none placeholder-transparent text-sm font-semibold transition-all duration-200"
                               placeholder=" ">
                        <label for="price" 
                               class="absolute right-4 text-gray-400 transition-all duration-300 pointer-events-none origin-top-right bg-white px-1.5"
                               :class="focus || filled ? 'top-0 -translate-y-1/2 scale-75 font-bold text-primary' : 'top-1/2 -translate-y-1/2 scale-100 text-gray-400'"
                               style="transform-origin: top right;">
                            السعر الأساسي (ر.س)
                        </label>
                    </div>

                    <!-- Discount Price (Floating Label) -->
                    <div class="relative w-full" x-data="{ focus: false, filled: false }">
                        <input type="number" name="discount_price" id="discount_price" step="0.01" min="0" value="{{ old('discount_price') }}"
                               @focus="focus = true"
                               @blur="focus = false; filled = ($el.value.length > 0)"
                               @input="filled = ($el.value.length > 0)"
                               class="w-full text-right bg-white text-gray-800 px-4 py-4 rounded-xl border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none placeholder-transparent text-sm font-semibold transition-all duration-200"
                               placeholder=" ">
                        <label for="discount_price" 
                               class="absolute right-4 text-gray-400 transition-all duration-300 pointer-events-none origin-top-right bg-white px-1.5"
                               :class="focus || filled ? 'top-0 -translate-y-1/2 scale-75 font-bold text-primary' : 'top-1/2 -translate-y-1/2 scale-100 text-gray-400'"
                               style="transform-origin: top right;">
                            سعر التخفيض (ر.س - اختياري)
                        </label>
                    </div>

                    <!-- Stock Quantity (Floating Label) -->
                    <div class="relative w-full" x-data="{ focus: false, filled: true }">
                        <input type="number" name="stock_quantity" id="stock_quantity" required min="0" value="{{ old('stock_quantity', 10) }}"
                               @focus="focus = true"
                               @blur="focus = false; filled = ($el.value.length > 0)"
                               @input="filled = ($el.value.length > 0)"
                               class="w-full text-right bg-white text-gray-800 px-4 py-4 rounded-xl border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none placeholder-transparent text-sm font-semibold transition-all duration-200"
                               placeholder=" ">
                        <label for="stock_quantity" 
                               class="absolute right-4 text-gray-400 transition-all duration-300 pointer-events-none origin-top-right bg-white px-1.5"
                               :class="focus || filled ? 'top-0 -translate-y-1/2 scale-75 font-bold text-primary' : 'top-1/2 -translate-y-1/2 scale-100 text-gray-400'"
                               style="transform-origin: top right;">
                            الكمية المتوفرة بالمخزون
                        </label>
                    </div>

                    <!-- Weight (Floating Label) -->
                    <div class="relative w-full" x-data="{ focus: false, filled: false }">
                        <input type="number" name="weight" id="weight" step="0.01" min="0" value="{{ old('weight') }}"
                               @focus="focus = true"
                               @blur="focus = false; filled = ($el.value.length > 0)"
                               @input="filled = ($el.value.length > 0)"
                               class="w-full text-right bg-white text-gray-800 px-4 py-4 rounded-xl border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none placeholder-transparent text-sm font-semibold transition-all duration-200"
                               placeholder=" ">
                        <label for="weight" 
                               class="absolute right-4 text-gray-400 transition-all duration-300 pointer-events-none origin-top-right bg-white px-1.5"
                               :class="focus || filled ? 'top-0 -translate-y-1/2 scale-75 font-bold text-primary' : 'top-1/2 -translate-y-1/2 scale-100 text-gray-400'"
                               style="transform-origin: top right;">
                            الوزن التقريبي (كجم - اختياري)
                        </label>
                    </div>
                </div>

                <!-- Description (Floating Label) -->
                <div class="relative w-full" x-data="{ focus: false, filled: false }">
                    <textarea name="description" id="description" rows="5"
                              @focus="focus = true"
                              @blur="focus = false; filled = ($el.value.length > 0)"
                              @input="filled = ($el.value.length > 0)"
                              class="w-full text-right bg-white text-gray-800 px-4 py-4 rounded-xl border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none placeholder-transparent text-sm font-semibold transition-all duration-200 min-h-[120px]">{{ old('description') }}</textarea>
                    <label for="description" 
                           class="absolute right-4 text-gray-400 transition-all duration-300 pointer-events-none origin-top-right bg-white px-1.5"
                           :class="focus || filled || '{{ old('description') }}' ? 'top-0 -translate-y-1/2 scale-75 font-bold text-primary' : 'top-4 scale-100 text-gray-400'"
                           style="transform-origin: top right;">
                        وصف المنتج
                    </label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Cover Image Upload Box with Preview -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2">صورة الغلاف (الكفر)</label>
                        <div class="relative border-2 border-dashed border-gray-200 rounded-xl p-4 hover:border-primary/50 transition bg-gray-50/50 flex flex-col items-center justify-center cursor-pointer group"
                             onclick="document.getElementById('product_image_btn').click()">
                            
                            <!-- Placeholder -->
                            <div id="add-product-placeholder" class="flex flex-col items-center justify-center text-center">
                                <span class="text-2xl mb-1 group-hover:scale-110 transition-transform">📷</span>
                                <span class="text-[10px] text-gray-500 font-bold">رفع صورة الغلاف الرئيسية</span>
                            </div>

                            <!-- Image Preview -->
                            <div id="add-product-preview-container" class="hidden w-full h-24 relative">
                                <img id="add-product-preview" src="#" alt="معاينة الغلاف" class="w-full h-full object-contain rounded-lg">
                                <div class="absolute inset-0 bg-black/40 opacity-0 hover:opacity-100 transition flex items-center justify-center rounded-lg">
                                    <span class="text-[9px] text-white font-bold bg-primary px-2.5 py-1 rounded-lg">تغيير الصورة 📷</span>
                                </div>
                            </div>

                            <input type="file" name="image" id="product_image_btn" accept="image/*" class="hidden" 
                                   onchange="previewImage(this, 'add-product-placeholder', 'add-product-preview-container', 'add-product-preview', 'product-image-name')">
                        </div>
                        <div id="product-image-name" class="text-xs text-primary font-bold mt-2 text-center" style="display: none;"></div>
                    </div>

                    <!-- Gallery Images Upload Box with Horizontal Previews + Remove -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2">صور المعرض الإضافية (سلايدر)</label>
                        
                        <!-- Preview Row -->
                        <div id="product-gallery-preview-row" class="hidden flex gap-2 overflow-x-auto pb-2 mb-2"></div>

                        <!-- Upload trigger -->
                        <div class="relative border-2 border-dashed border-gray-200 rounded-xl p-4 hover:border-primary/50 transition bg-gray-50/50 flex flex-col items-center justify-center cursor-pointer group"
                             onclick="document.getElementById('product_gallery_btn').click()">
                            <span class="text-2xl mb-1 group-hover:scale-110 transition-transform">🖼️</span>
                            <span class="text-[10px] text-gray-500 font-bold">اضغط لإضافة صور المعرض</span>
                            <input type="file" name="images[]" id="product_gallery_btn" accept="image/*" multiple class="hidden"
                                   onchange="addGalleryFiles(this, 'product_gallery_btn', 'product-gallery-preview-row')">
                        </div>
                    </div>
                </div>

                <!-- Is Available Checkbox -->
                <div class="flex items-center gap-3 py-1">
                    <input type="checkbox" name="is_available" id="is_available" value="1" checked
                           class="rounded border-gray-300 text-primary focus:ring-primary h-5 w-5">
                    <label for="is_available" class="font-bold text-gray-700 text-xs cursor-pointer">عرض المنتج للبيع فوراً بالموقع</label>
                </div>

                <!-- Submit & Cancel Buttons -->
                <div class="flex gap-3 pt-3 flex-shrink-0">
                    <button type="submit" class="flex-1 bg-primary hover:bg-primary-dark text-white font-extrabold py-3.5 rounded-xl transition shadow cursor-pointer text-sm">
                        إضافة المنتج
                    </button>
                    <button type="button" @click="addModalOpen = false" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-extrabold py-3.5 rounded-xl transition cursor-pointer text-sm">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT PRODUCT DIALOG (MODAL) -->
    <div x-show="editModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto" style="display: none;">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity" @click="editModalOpen = false"></div>

        <!-- Modal Content -->
        <div class="relative transform overflow-hidden rounded-2xl bg-white p-8 text-right shadow-2xl transition-all w-full max-w-2xl border border-gray-100/50 z-10 my-8 max-h-[90vh] flex flex-col"
             x-show="editModalOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4">
            
            <!-- Close Button -->
            <button @click="editModalOpen = false" class="absolute top-4 left-4 text-gray-400 hover:text-gray-600 focus:outline-none cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <!-- Modal Title -->
            <div class="mb-6 flex items-center gap-2 flex-shrink-0">
                <span class="text-2xl">🥩</span>
                <div>
                    <h3 class="text-xl font-extrabold text-gray-900">تعديل المنتج</h3>
                    <p class="text-gray-400 text-xs mt-0.5">تعديل بيانات ومواصفات المنتج المحدد</p>
                </div>
            </div>

            <!-- Scrollable Form Body -->
            <form :action="'{{ url('admin/products') }}/' + editProduct.id" method="POST" enctype="multipart/form-data" class="space-y-5 overflow-y-auto flex-grow pr-1">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-4" x-init="
                    $watch('editProduct.name', v => $refs.edit_name_container.__x.$data.filled = (v && v.length > 0));
                    $watch('editProduct.category_id', v => $refs.edit_cat_container.__x.$data.filled = (v && v.length > 0));
                    $watch('editProduct.price', v => $refs.edit_price_container.__x.$data.filled = (v && v.length > 0));
                    $watch('editProduct.discount_price', v => $refs.edit_discount_container.__x.$data.filled = (v && v.length > 0));
                    $watch('editProduct.stock_quantity', v => $refs.edit_stock_container.__x.$data.filled = (v && v.length > 0));
                    $watch('editProduct.weight', v => $refs.edit_weight_container.__x.$data.filled = (v && v.length > 0));
                    $watch('editProduct.description', v => $refs.edit_desc_container.__x.$data.filled = (v && v.length > 0));
                ">
                    <!-- Name (Floating Label) -->
                    <div class="relative w-full" x-data="{ focus: false, filled: false }" x-ref="edit_name_container">
                        <input type="text" name="name" id="edit_name" required x-model="editProduct.name"
                               @focus="focus = true"
                               @blur="focus = false; filled = ($el.value.length > 0)"
                               @input="filled = ($el.value.length > 0)"
                               class="w-full text-right bg-white text-gray-800 px-4 py-4 rounded-xl border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none placeholder-transparent text-sm font-semibold transition-all duration-200"
                               placeholder=" ">
                        <label for="edit_name" 
                               class="absolute right-4 text-gray-400 transition-all duration-300 pointer-events-none origin-top-right bg-white px-1.5"
                               :class="focus || filled || (editProduct.name && editProduct.name.length > 0) ? 'top-0 -translate-y-1/2 scale-75 font-bold text-primary' : 'top-1/2 -translate-y-1/2 scale-100 text-gray-400'"
                               style="transform-origin: top right;">
                            اسم المنتج/العنوان (العربية)
                        </label>
                    </div>

                    <!-- Category (Floating Label) -->
                    <div class="relative w-full" x-data="{ focus: false, filled: false }" x-ref="edit_cat_container">
                        <select name="category_id" id="edit_category_id" required x-model="editProduct.category_id"
                                @focus="focus = true"
                                @blur="focus = false; filled = ($el.value.length > 0)"
                                @change="filled = ($el.value.length > 0)"
                                class="w-full text-right bg-white text-gray-800 px-4 py-4 rounded-xl border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none placeholder-transparent text-sm font-semibold transition-all duration-200 appearance-none">
                            <option value=""></option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <label for="edit_category_id" 
                               class="absolute right-4 text-gray-400 transition-all duration-300 pointer-events-none origin-top-right bg-white px-1.5"
                               :class="focus || filled || (editProduct.category_id && editProduct.category_id.length > 0) ? 'top-0 -translate-y-1/2 scale-75 font-bold text-primary' : 'top-1/2 -translate-y-1/2 scale-100 text-gray-400'"
                               style="transform-origin: top right;">
                            التصنيف (القسم)
                        </label>
                    </div>

                    <!-- Price (Floating Label) -->
                    <div class="relative w-full" x-data="{ focus: false, filled: false }" x-ref="edit_price_container">
                        <input type="number" name="price" id="edit_price" required step="0.01" min="0" x-model="editProduct.price"
                               @focus="focus = true"
                               @blur="focus = false; filled = ($el.value.length > 0)"
                               @input="filled = ($el.value.length > 0)"
                               class="w-full text-right bg-white text-gray-800 px-4 py-4 rounded-xl border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none placeholder-transparent text-sm font-semibold transition-all duration-200"
                               placeholder=" ">
                        <label for="edit_price" 
                               class="absolute right-4 text-gray-400 transition-all duration-300 pointer-events-none origin-top-right bg-white px-1.5"
                               :class="focus || filled || (editProduct.price && editProduct.price.length > 0) ? 'top-0 -translate-y-1/2 scale-75 font-bold text-primary' : 'top-1/2 -translate-y-1/2 scale-100 text-gray-400'"
                               style="transform-origin: top right;">
                            السعر الأساسي (ر.س)
                        </label>
                    </div>

                    <!-- Discount Price (Floating Label) -->
                    <div class="relative w-full" x-data="{ focus: false, filled: false }" x-ref="edit_discount_container">
                        <input type="number" name="discount_price" id="edit_discount_price" step="0.01" min="0" x-model="editProduct.discount_price"
                               @focus="focus = true"
                               @blur="focus = false; filled = ($el.value.length > 0)"
                               @input="filled = ($el.value.length > 0)"
                               class="w-full text-right bg-white text-gray-800 px-4 py-4 rounded-xl border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none placeholder-transparent text-sm font-semibold transition-all duration-200"
                               placeholder=" ">
                        <label for="edit_discount_price" 
                               class="absolute right-4 text-gray-400 transition-all duration-300 pointer-events-none origin-top-right bg-white px-1.5"
                               :class="focus || filled || (editProduct.discount_price && editProduct.discount_price.length > 0) ? 'top-0 -translate-y-1/2 scale-75 font-bold text-primary' : 'top-1/2 -translate-y-1/2 scale-100 text-gray-400'"
                               style="transform-origin: top right;">
                            سعر التخفيض (ر.س - اختياري)
                        </label>
                    </div>

                    <!-- Stock Quantity (Floating Label) -->
                    <div class="relative w-full" x-data="{ focus: false, filled: false }" x-ref="edit_stock_container">
                        <input type="number" name="stock_quantity" id="edit_stock_quantity" required min="0" x-model="editProduct.stock_quantity"
                               @focus="focus = true"
                               @blur="focus = false; filled = ($el.value.length > 0)"
                               @input="filled = ($el.value.length > 0)"
                               class="w-full text-right bg-white text-gray-800 px-4 py-4 rounded-xl border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none placeholder-transparent text-sm font-semibold transition-all duration-200"
                               placeholder=" ">
                        <label for="edit_stock_quantity" 
                               class="absolute right-4 text-gray-400 transition-all duration-300 pointer-events-none origin-top-right bg-white px-1.5"
                               :class="focus || filled || (editProduct.stock_quantity && editProduct.stock_quantity.length > 0) ? 'top-0 -translate-y-1/2 scale-75 font-bold text-primary' : 'top-1/2 -translate-y-1/2 scale-100 text-gray-400'"
                               style="transform-origin: top right;">
                            الكمية المتوفرة بالمخزون
                        </label>
                    </div>

                    <!-- Weight (Floating Label) -->
                    <div class="relative w-full" x-data="{ focus: false, filled: false }" x-ref="edit_weight_container">
                        <input type="number" name="weight" id="edit_weight" step="0.01" min="0" x-model="editProduct.weight"
                               @focus="focus = true"
                               @blur="focus = false; filled = ($el.value.length > 0)"
                               @input="filled = ($el.value.length > 0)"
                               class="w-full text-right bg-white text-gray-800 px-4 py-4 rounded-xl border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none placeholder-transparent text-sm font-semibold transition-all duration-200"
                               placeholder=" ">
                        <label for="edit_weight" 
                               class="absolute right-4 text-gray-400 transition-all duration-300 pointer-events-none origin-top-right bg-white px-1.5"
                               :class="focus || filled || (editProduct.weight && editProduct.weight.length > 0) ? 'top-0 -translate-y-1/2 scale-75 font-bold text-primary' : 'top-1/2 -translate-y-1/2 scale-100 text-gray-400'"
                               style="transform-origin: top right;">
                            الوزن التقريبي (كجم - اختياري)
                        </label>
                    </div>
                </div>

                <!-- Description (Floating Label) -->
                <div class="relative w-full mt-6" x-data="{ focus: false, filled: false }" x-ref="edit_desc_container">
                    <textarea name="description" id="edit_description" rows="5" x-model="editProduct.description"
                              @focus="focus = true"
                              @blur="focus = false; filled = ($el.value.length > 0)"
                              @input="filled = ($el.value.length > 0)"
                              class="w-full text-right bg-white text-gray-800 px-4 py-4 rounded-xl border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none placeholder-transparent text-sm font-semibold transition-all duration-200 min-h-[120px]"></textarea>
                    <label for="edit_description" 
                           class="absolute right-4 text-gray-400 transition-all duration-300 pointer-events-none origin-top-right bg-white px-1.5"
                           :class="focus || filled || (editProduct.description && editProduct.description.length > 0) ? 'top-0 -translate-y-1/2 scale-75 font-bold text-primary' : 'top-4 scale-100 text-gray-400'"
                           style="transform-origin: top right;">
                        وصف المنتج
                    </label>
                </div>

                <!-- Hidden inputs for deleted gallery images -->
                <template x-for="path in editProduct.pathsToDelete" :key="path">
                    <input type="hidden" name="delete_gallery[]" :value="path">
                </template>

                <!-- Current Images Display -->
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 space-y-3 mt-6">
                    <!-- Current Cover Image -->
                    <div x-show="editProduct.imageUrl">
                        <span class="block text-xs font-bold text-gray-700 mb-2">صورة الغلاف الحالية</span>
                        <div class="w-20 h-20 rounded-lg overflow-hidden border border-gray-200 bg-white flex items-center justify-center text-xl">
                            <img :src="editProduct.imageUrl" alt="" class="w-full h-full object-cover">
                        </div>
                    </div>

                    <!-- Current Gallery Images — Horizontal Row with Delete -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="block text-xs font-bold text-gray-700">صور المعرض الحالية</span>
                            <span class="text-[10px] text-gray-400 font-semibold">اضغط ✕ لحذف صورة</span>
                        </div>
                        <div class="flex gap-3 overflow-x-auto pb-2">
                            <template x-for="(url, index) in editProduct.galleryUrls" :key="url">
                                <div class="relative flex-shrink-0 w-20 h-20 group border border-gray-100 rounded-xl p-0.5 bg-white">
                                    <img :src="url" class="w-full h-full object-cover rounded-lg">
                                    <button type="button"
                                            @click.prevent="removeExistingGalleryImage(index)"
                                            class="absolute -top-2 -right-2 bg-red-600 hover:bg-red-700 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs transition-all cursor-pointer z-10 shadow-md border border-white font-extrabold"
                                            title="حذف الصورة">
                                        ✕
                                    </button>
                                </div>
                            </template>
                            <template x-if="editProduct.galleryUrls.length === 0">
                                <span class="text-[10px] text-gray-400 font-bold block pt-5">لا توجد صور إضافية.</span>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                    <!-- Cover Image Upload Box with Preview -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2">تحديث صورة الغلاف (الكفر)</label>
                        <div class="relative border-2 border-dashed border-gray-200 rounded-xl p-4 hover:border-primary/50 transition bg-gray-50/50 flex flex-col items-center justify-center cursor-pointer group"
                             onclick="document.getElementById('edit_product_image_btn').click()">
                            
                            <!-- Placeholder -->
                            <div id="edit-product-placeholder" class="flex flex-col items-center justify-center text-center">
                                <span class="text-2xl mb-1 group-hover:scale-110 transition-transform">📷</span>
                                <span class="text-[10px] text-gray-500 font-bold">رفع صورة غلاف جديدة</span>
                            </div>

                            <!-- Image Preview -->
                            <div id="edit-product-preview-container" class="hidden w-full h-24 relative">
                                <img id="edit-product-preview" src="#" alt="معاينة الغلاف" class="w-full h-full object-contain rounded-lg">
                                <div class="absolute inset-0 bg-black/40 opacity-0 hover:opacity-100 transition flex items-center justify-center rounded-lg">
                                    <span class="text-[9px] text-white font-bold bg-primary px-2.5 py-1 rounded-lg">تغيير الصورة 📷</span>
                                </div>
                            </div>

                            <input type="file" name="image" id="edit_product_image_btn" accept="image/*" class="hidden" 
                                   onchange="previewImage(this, 'edit-product-placeholder', 'edit-product-preview-container', 'edit-product-preview', 'edit-product-image-name')">
                        </div>
                        <div id="edit-product-image-name" class="text-xs text-primary font-bold mt-2 text-center" style="display: none;"></div>
                    </div>

                    <!-- Gallery Images Upload (Add New) — Horizontal Row with Remove -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2">إضافة صور معرض جديدة</label>

                        <!-- New upload preview row -->
                        <div id="edit-product-gallery-preview-row" class="hidden flex gap-2 overflow-x-auto pb-2 mb-2"></div>

                        <!-- Upload trigger -->
                        <div class="relative border-2 border-dashed border-gray-200 rounded-xl p-4 hover:border-primary/50 transition bg-gray-50/50 flex flex-col items-center justify-center cursor-pointer group"
                             onclick="document.getElementById('edit_product_gallery_btn').click()">
                            <span class="text-2xl mb-1 group-hover:scale-110 transition-transform">🖼️</span>
                            <span class="text-[10px] text-gray-500 font-bold">اضغط لإضافة صور جديدة</span>
                            <input type="file" name="images[]" id="edit_product_gallery_btn" accept="image/*" multiple class="hidden"
                                   onchange="addGalleryFiles(this, 'edit_product_gallery_btn', 'edit-product-gallery-preview-row')">
                        </div>
                    </div>
                </div>

                <!-- Is Available Checkbox -->
                <div class="flex items-center gap-3 py-1">
                    <input type="checkbox" name="is_available" id="edit_is_available" value="1" x-model="editProduct.is_available"
                           class="rounded border-gray-300 text-primary focus:ring-primary h-5 w-5">
                    <label for="edit_is_available" class="font-bold text-gray-700 text-xs cursor-pointer">عرض المنتج للبيع بالموقع</label>
                </div>

                <!-- Submit & Cancel Buttons -->
                <div class="flex gap-3 pt-3 flex-shrink-0">
                    <button type="submit" class="flex-1 bg-primary hover:bg-primary-dark text-white font-extrabold py-3.5 rounded-xl transition shadow cursor-pointer text-sm">
                        تحديث المنتج
                    </button>
                    <button type="button" @click="editModalOpen = false" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-extrabold py-3.5 rounded-xl transition cursor-pointer text-sm">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function previewImage(input, placeholderId, containerId, imgId, nameId) {
        const placeholder = document.getElementById(placeholderId);
        const container = document.getElementById(containerId);
        const img = document.getElementById(imgId);
        const nameText = document.getElementById(nameId);

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                placeholder.style.display = 'none';
                container.style.display = 'block';
                if (nameText) {
                    nameText.textContent = '📁 تم اختيار: ' + input.files[0].name;
                    nameText.style.display = 'block';
                }
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            img.src = '#';
            placeholder.style.display = 'flex';
            container.style.display = 'none';
            if (nameText) {
                nameText.style.display = 'none';
            }
        }
    }

    // ------ Gallery Files Manager (Add + Remove individually) ------
    const galleryFileSets = {};

    function addGalleryFiles(input, inputId, previewRowId) {
        if (!galleryFileSets[inputId]) galleryFileSets[inputId] = [];

        const newFiles = Array.from(input.files);
        galleryFileSets[inputId].push(...newFiles);
        input.value = ''; // reset so same file can be picked again

        renderGalleryPreview(inputId, previewRowId);
    }

    function renderGalleryPreview(inputId, previewRowId) {
        const files = galleryFileSets[inputId] || [];
        const row = document.getElementById(previewRowId);
        if (!row) return;
        row.innerHTML = '';

        if (files.length === 0) {
            row.style.display = 'none';
            syncFilesToInput(inputId);
            return;
        }

        row.style.display = 'flex';

        files.forEach((file, index) => {
            const wrap = document.createElement('div');
            wrap.className = 'relative flex-shrink-0 w-20 h-20 group border border-gray-100 rounded-xl p-0.5 bg-white';

            const img = document.createElement('img');
            img.className = 'w-full h-full object-cover rounded-lg';

            const reader = new FileReader();
            reader.onload = e => { img.src = e.target.result; };
            reader.readAsDataURL(file);

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'absolute -top-2 -right-2 bg-red-600 hover:bg-red-700 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs transition-all cursor-pointer z-10 shadow-md border border-white font-extrabold';
            btn.textContent = '✕';
            btn.onclick = function() {
                galleryFileSets[inputId].splice(index, 1);
                renderGalleryPreview(inputId, previewRowId);
            };

            wrap.appendChild(img);
            wrap.appendChild(btn);
            row.appendChild(wrap);
        });

        syncFilesToInput(inputId);
    }

    function syncFilesToInput(inputId) {
        const files = galleryFileSets[inputId] || [];
        const input = document.getElementById(inputId);
        if (!input) return;
        try {
            const dt = new DataTransfer();
            files.forEach(f => dt.items.add(f));
            input.files = dt.files;
        } catch(e) { /* DataTransfer not supported */ }
    }

    function confirmDelete(productId) {
        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: 'هل تريد حذف هذا المنتج نهائياً؟ لا يمكن التراجع عن هذا الإجراء.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#7A0C0C',
            cancelButtonColor: '#B22222',
            confirmButtonText: 'نعم، احذف المنتج',
            cancelButtonText: 'إلغاء',
            customClass: {
                popup: 'rounded-3xl',
                confirmButton: 'rounded-xl px-4 py-2 font-bold',
                cancelButton: 'rounded-xl px-4 py-2 font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + productId).submit();
            }
        });
    }
</script>

<style>
    /* Hide spin-buttons for input number */
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>
@endsection
