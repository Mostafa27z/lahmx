@extends('layouts.admin')

@section('title', 'إضافة قسم جديد - لوحة التحكم')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.categories.index') }}" class="text-gray-500 hover:text-gray-700 font-bold">&larr; العودة لقائمة الأقسام</a>
        <h1 class="text-3xl font-bold text-gray-900">إضافة قسم جديد 📁</h1>
    </div>

    <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm">
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-bold text-gray-700 mb-2">اسم القسم (العربية)</label>
                <input type="text" name="name" id="name" required value="{{ old('name') }}"
                       class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-right">
                @error('name')
                    <span class="text-red-600 text-xs font-semibold mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Image Upload -->
            <div>
                <label for="image" class="block text-sm font-bold text-gray-700 mb-2">صورة القسم</label>
                <input type="file" name="image" id="image" accept="image/*"
                       class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:outline-none file:bg-primary file:text-white file:border-none file:px-4 file:py-1.5 file:rounded-lg file:font-bold file:ml-4 cursor-pointer">
                @error('image')
                    <span class="text-red-600 text-xs font-semibold mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Is Active -->
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1" checked
                       class="rounded border-gray-300 text-primary focus:ring-primary h-5 w-5">
                <label for="is_active" class="font-bold text-gray-700">تفعيل القسم فوراً بالموقع</label>
            </div>

            <!-- Submit -->
            <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-extrabold py-3.5 rounded-xl transition shadow shadow-md">
                إضافة القسم
            </button>
        </form>
    </div>
</div>
@endsection
