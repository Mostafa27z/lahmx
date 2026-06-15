@extends('layouts.admin')

@section('title', 'إدارة الفئات - لوحة التحكم')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">إدارة الأقسام (الفئات) 📁</h1>
            <p class="text-gray-500 mt-1">عرض وتعديل فئات اللحوم بالمتجر</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="bg-primary hover:bg-primary-dark text-white font-bold px-6 py-3 rounded-lg transition shadow">إضافة قسم جديد +</a>
    </div>

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
                @foreach($categories as $category)
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
                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="text-blue-600 hover:text-blue-800 transition">تعديل 📝</a>
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه الفئة؟ سيتم حذف جميع المنتجات التابعة لها.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 transition">حذف 🗑️</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="p-6 border-t border-gray-50">
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection
