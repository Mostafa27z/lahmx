@extends('layouts.admin')

@section('title', 'إدارة الفئات - لوحة التحكم')

@section('content')
<div class="space-y-6" x-data="{ 
    addModalOpen: false, 
    editModalOpen: false,
    editCategory: { id: null, name: '', is_active: true, imageUrl: '' },
    openEditModal(id, name, isActive, imageUrl) {
        this.editCategory = { id: id, name: name, is_active: isActive, imageUrl: imageUrl };
        this.editModalOpen = true;
    }
}">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">إدارة الأقسام (الفئات) 📁</h1>
            <p class="text-gray-500 mt-1">عرض وتعديل فئات اللحوم بالمتجر</p>
        </div>
        <button @click="addModalOpen = true" class="bg-primary hover:bg-primary-dark text-white font-bold px-6 py-3 rounded-lg transition shadow cursor-pointer">
            إضافة قسم جديد +
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
                    <th class="p-6">اسم القسم</th>
                    <th class="p-6">الرابط الفريد (Slug)</th>
                    <th class="p-6">الحالة</th>
                    <th class="p-6 text-left">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 font-semibold text-gray-700">
                @forelse($categories as $category)
                    <tr class="hover:bg-gray-50/50">
                        <td class="p-6">
                            <div class="w-12 h-12 rounded-xl bg-gray-50 border border-gray-100 overflow-hidden flex items-center justify-center text-xl">
                                @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" alt="" class="w-full h-full object-cover">
                                @else
                                    🥩
                                @endif
                            </div>
                        </td>
                        <td class="p-6 font-bold text-gray-800">{{ $category->name }}</td>
                        <td class="p-6 text-sm text-gray-400">{{ $category->slug }}</td>
                        <td class="p-6">
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $category->is_active ? 'نشط' : 'غير نشط' }}
                            </span>
                        </td>
                        <td class="p-6 text-left flex items-center justify-end gap-3">
                            <button @click="openEditModal({{ $category->id }}, '{{ addslashes($category->name) }}', {{ $category->is_active ? 'true' : 'false' }}, '{{ $category->image ? asset('storage/' . $category->image) : '' }}')" 
                                    class="text-blue-600 hover:text-blue-800 font-bold transition cursor-pointer">تعديل 📝</button>
                            <button type="button" onclick="confirmDelete({{ $category->id }}, '{{ addslashes($category->name) }}')" class="text-red-600 hover:text-red-800 transition cursor-pointer">حذف 🗑️</button>
                            <form id="delete-form-{{ $category->id }}" action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-12 text-center text-gray-400 font-bold">لا توجد أقسام حالياً.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="p-6 border-t border-gray-50">
            {{ $categories->links() }}
        </div>
    </div>

    <!-- ADD CATEGORY DIALOG (MODAL) -->
    <div x-show="addModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity" @click="addModalOpen = false"></div>

        <!-- Modal Content -->
        <div class="relative transform overflow-hidden rounded-2xl bg-white p-8 text-right shadow-2xl transition-all w-full max-w-md border border-gray-100/50 z-10"
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
            <div class="mb-6 flex items-center gap-2">
                <span class="text-2xl">📁</span>
                <div>
                    <h3 class="text-xl font-extrabold text-gray-900">إضافة قسم جديد</h3>
                    <p class="text-gray-400 text-xs mt-0.5">أدخل بيانات القسم الجديد لتفعيله في المتجر</p>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

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
                        اسم القسم (العربية)
                    </label>
                </div>

                <!-- Custom Image Upload Box with Live Preview -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-2">صورة القسم</label>
                    <div class="relative border-2 border-dashed border-gray-200 rounded-xl p-5 hover:border-primary/50 transition bg-gray-50/50 flex flex-col items-center justify-center cursor-pointer group"
                         onclick="document.getElementById('image_upload_btn').click()">
                        
                        <!-- Upload Placeholder -->
                        <div id="add-upload-placeholder" class="flex flex-col items-center justify-center text-center">
                            <span class="text-3xl mb-1.5 group-hover:scale-110 transition-transform">📷</span>
                            <span class="text-xs text-gray-500 font-bold">اضغط هنا لرفع صورة القسم</span>
                            <span class="text-[10px] text-gray-400 mt-1 font-semibold">الملفات المدعومة: JPG, PNG, WEBP</span>
                        </div>

                        <!-- Live Preview -->
                        <div id="add-upload-preview-container" class="hidden w-full h-32 relative">
                            <img id="add-upload-preview" src="#" alt="معاينة الصورة" class="w-full h-full object-contain rounded-lg">
                            <div class="absolute inset-0 bg-black/40 opacity-0 hover:opacity-100 transition flex items-center justify-center rounded-lg">
                                <span class="text-white text-xs font-bold bg-primary px-3 py-1.5 rounded-lg">تغيير الصورة 📷</span>
                            </div>
                        </div>

                        <input type="file" name="image" id="image_upload_btn" accept="image/*" class="hidden" 
                               onchange="previewImage(this, 'add-upload-placeholder', 'add-upload-preview-container', 'add-upload-preview', 'add-file-name')">
                    </div>
                    <div id="add-file-name" class="text-xs text-primary font-bold mt-2 text-center" style="display: none;"></div>
                </div>

                <!-- Is Active Checkbox -->
                <div class="flex items-center gap-3 py-1.5">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked
                           class="rounded border-gray-300 text-primary focus:ring-primary h-5 w-5">
                    <label for="is_active" class="font-bold text-gray-700 text-xs cursor-pointer">تفعيل القسم فوراً بالموقع</label>
                </div>

                <!-- Submit & Cancel Buttons -->
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="flex-1 bg-primary hover:bg-primary-dark text-white font-extrabold py-3.5 rounded-xl transition shadow cursor-pointer text-sm">
                        إضافة القسم
                    </button>
                    <button type="button" @click="addModalOpen = false" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-extrabold py-3.5 rounded-xl transition cursor-pointer text-sm">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT CATEGORY DIALOG (MODAL) -->
    <div x-show="editModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity" @click="editModalOpen = false"></div>

        <!-- Modal Content -->
        <div class="relative transform overflow-hidden rounded-2xl bg-white p-8 text-right shadow-2xl transition-all w-full max-w-md border border-gray-100/50 z-10"
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
            <div class="mb-6 flex items-center gap-2">
                <span class="text-2xl">📁</span>
                <div>
                    <h3 class="text-xl font-extrabold text-gray-900">تعديل القسم</h3>
                    <p class="text-gray-400 text-xs mt-0.5">تعديل بيانات القسم المحدد</p>
                </div>
            </div>

            <!-- Form -->
            <form :action="'{{ url('admin/categories') }}/' + editCategory.id" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- Name (Floating Label) -->
                <div class="relative w-full" x-data="{ focus: false, filled: false }" x-init="$watch('editCategory.name', value => filled = (value && value.length > 0))">
                    <input type="text" name="name" id="edit_name" required x-model="editCategory.name"
                           @focus="focus = true"
                           @blur="focus = false; filled = ($el.value.length > 0)"
                           @input="filled = ($el.value.length > 0)"
                           class="w-full text-right bg-white text-gray-800 px-4 py-4 rounded-xl border border-gray-200 focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none placeholder-transparent text-sm font-semibold transition-all duration-200"
                           placeholder=" ">
                    <label for="edit_name" 
                           class="absolute right-4 text-gray-400 transition-all duration-300 pointer-events-none origin-top-right bg-white px-1.5"
                           :class="focus || filled || (editCategory.name && editCategory.name.length > 0) ? 'top-0 -translate-y-1/2 scale-75 font-bold text-primary' : 'top-1/2 -translate-y-1/2 scale-100 text-gray-400'"
                           style="transform-origin: top right;">
                        اسم القسم
                    </label>
                </div>

                <!-- Custom Image Upload Box with Live Preview -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-2">صورة القسم</label>
                    
                    <!-- Pre-existing Image Preview -->
                    <template x-if="editCategory.imageUrl && !document.getElementById('edit_image_upload_btn').files.length">
                        <div class="flex items-center gap-4 bg-gray-50 p-3 rounded-xl border border-gray-100 mb-3">
                            <img :src="editCategory.imageUrl" alt="" class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                            <div>
                                <span class="block text-xs font-bold text-gray-700">الصورة الحالية للقسم</span>
                                <span class="block text-[10px] text-gray-400 mt-0.5">سيتم الحفاظ عليها ما لم تقم برفع صورة جديدة</span>
                            </div>
                        </div>
                    </template>

                    <div class="relative border-2 border-dashed border-gray-200 rounded-xl p-5 hover:border-primary/50 transition bg-gray-50/50 flex flex-col items-center justify-center cursor-pointer group"
                         onclick="document.getElementById('edit_image_upload_btn').click()">
                        
                        <!-- Upload Placeholder -->
                        <div id="edit-upload-placeholder" class="flex flex-col items-center justify-center text-center">
                            <span class="text-3xl mb-1.5 group-hover:scale-110 transition-transform">📷</span>
                            <span class="text-xs text-gray-500 font-bold">اضغط هنا لرفع صورة جديدة</span>
                            <span class="text-[10px] text-gray-400 mt-1 font-semibold">اتركها فارغة للاحتفاظ بالصورة الحالية</span>
                        </div>

                        <!-- Live Preview -->
                        <div id="edit-upload-preview-container" class="hidden w-full h-32 relative">
                            <img id="edit-upload-preview" src="#" alt="معاينة الصورة" class="w-full h-full object-contain rounded-lg">
                            <div class="absolute inset-0 bg-black/40 opacity-0 hover:opacity-100 transition flex items-center justify-center rounded-lg">
                                <span class="text-white text-xs font-bold bg-primary px-3 py-1.5 rounded-lg">تغيير الصورة 📷</span>
                            </div>
                        </div>

                        <input type="file" name="image" id="edit_image_upload_btn" accept="image/*" class="hidden" 
                               onchange="previewImage(this, 'edit-upload-placeholder', 'edit-upload-preview-container', 'edit-upload-preview', 'edit-file-name')">
                    </div>
                    <div id="edit-file-name" class="text-xs text-primary font-bold mt-2 text-center" style="display: none;"></div>
                </div>

                <!-- Is Active Checkbox -->
                <div class="flex items-center gap-3 py-1.5">
                    <input type="checkbox" name="is_active" id="edit_is_active" value="1" x-model="editCategory.is_active"
                           class="rounded border-gray-300 text-primary focus:ring-primary h-5 w-5">
                    <label for="edit_is_active" class="font-bold text-gray-700 text-xs cursor-pointer">تفعيل القسم بالموقع</label>
                </div>

                <!-- Submit & Cancel Buttons -->
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="flex-1 bg-primary hover:bg-primary-dark text-white font-extrabold py-3.5 rounded-xl transition shadow cursor-pointer text-sm">
                        تحديث القسم
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
    function confirmDelete(id, name) {
        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: `سيتم حذف فئة "${name}" وجميع المنتجات التابعة لها نهائياً!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#7A0C0C',
            cancelButtonColor: '#B22222',
            confirmButtonText: 'نعم، احذفها! 🗑️',
            cancelButtonText: 'إلغاء',
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-xl font-bold py-3 px-6',
                cancelButton: 'rounded-xl font-bold py-3 px-6'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    }

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
</script>
@endsection
