@extends('layouts.admin')

@section('title', 'إدارة المستخدمين - لوحة التحكم')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div>
        <h1 class="text-3xl font-bold text-gray-900">إدارة المستخدمين 👥</h1>
        <p class="text-gray-500 mt-1">عرض وإدارة جميع مستخدمي المتجر وتحديد صلاحياتهم</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <a href="{{ route('admin.customers.index', ['role' => 'all']) }}"
           class="bg-white rounded-2xl p-5 border shadow-sm flex items-center justify-between transition hover:shadow-md
                  {{ $role === 'all' ? 'border-primary ring-2 ring-primary/20' : 'border-gray-100' }}">
            <div>
                <p class="text-xs font-bold text-gray-400 mb-0.5">إجمالي المستخدمين</p>
                <p class="text-3xl font-extrabold text-gray-800">{{ $totalUsers }}</p>
            </div>
            <span class="text-4xl bg-gray-50 p-3 rounded-2xl">👥</span>
        </a>
        <a href="{{ route('admin.customers.index', ['role' => 'admin']) }}"
           class="bg-white rounded-2xl p-5 border shadow-sm flex items-center justify-between transition hover:shadow-md
                  {{ $role === 'admin' ? 'border-primary ring-2 ring-primary/20' : 'border-gray-100' }}">
            <div>
                <p class="text-xs font-bold text-gray-400 mb-0.5">المسؤولون</p>
                <p class="text-3xl font-extrabold text-primary">{{ $totalAdmins }}</p>
            </div>
            <span class="text-4xl bg-red-50 p-3 rounded-2xl">🛠️</span>
        </a>
        <a href="{{ route('admin.customers.index', ['role' => 'customer']) }}"
           class="bg-white rounded-2xl p-5 border shadow-sm flex items-center justify-between transition hover:shadow-md
                  {{ $role === 'customer' ? 'border-primary ring-2 ring-primary/20' : 'border-gray-100' }}">
            <div>
                <p class="text-xs font-bold text-gray-400 mb-0.5">العملاء</p>
                <p class="text-3xl font-extrabold text-gray-800">{{ $totalCustomers }}</p>
            </div>
            <span class="text-4xl bg-gray-50 p-3 rounded-2xl">🛒</span>
        </a>
    </div>

    {{-- Search Bar --}}
    <form method="GET" action="{{ route('admin.customers.index') }}" class="flex gap-3">
        <input type="hidden" name="role" value="{{ $role }}">
        <div class="relative flex-1">
            <i class="fa-solid fa-magnifying-glass absolute top-1/2 -translate-y-1/2 right-4 text-gray-400 text-sm"></i>
            <input type="text" name="search" value="{{ $search }}" placeholder="ابحث بالاسم أو البريد أو الهاتف..."
                   class="w-full pr-11 pl-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary text-right font-semibold text-sm">
        </div>
        <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-bold px-6 py-3 rounded-xl transition cursor-pointer text-sm">
            بحث
        </button>
        @if($search)
            <a href="{{ route('admin.customers.index', ['role' => $role]) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold px-5 py-3 rounded-xl transition text-sm flex items-center">
                مسح ✕
            </a>
        @endif
    </form>

    {{-- Users Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-right border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 font-bold border-b border-gray-100 text-xs">
                    <th class="p-5">#</th>
                    <th class="p-5">المستخدم</th>
                    <th class="p-5">البريد الإلكتروني</th>
                    <th class="p-5">رقم الهاتف</th>
                    <th class="p-5">تاريخ التسجيل</th>
                    <th class="p-5 text-center">الدور الحالي</th>
                    <th class="p-5 text-center">تغيير الدور</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-gray-700">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50/60 transition {{ $user->id === auth()->id() ? 'bg-primary/5' : '' }}">
                        {{-- # --}}
                        <td class="p-5 text-xs text-gray-400 font-bold">{{ $user->id }}</td>

                        {{-- Name + avatar --}}
                        <td class="p-5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center font-extrabold text-sm flex-shrink-0
                                            {{ $user->isAdmin() ? 'bg-primary/15 text-primary' : 'bg-gray-100 text-gray-600' }}">
                                    {{ mb_substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 text-sm">
                                        {{ $user->name }}
                                        @if($user->id === auth()->id())
                                            <span class="text-[10px] bg-primary/10 text-primary px-1.5 py-0.5 rounded-full font-bold mr-1">أنت</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </td>

                        {{-- Email --}}
                        <td class="p-5 text-sm text-gray-500 font-semibold" dir="ltr">{{ $user->email }}</td>

                        {{-- Phone --}}
                        <td class="p-5 text-sm text-gray-500 font-semibold">{{ $user->phone ?? '—' }}</td>

                        {{-- Date --}}
                        <td class="p-5 text-xs text-gray-400 font-semibold">
                            {{ $user->created_at->format('d/m/Y') }}
                        </td>

                        {{-- Current Role Badge --}}
                        <td class="p-5 text-center">
                            @if($user->isAdmin())
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-primary/10 text-primary">
                                    <i class="fa-solid fa-screwdriver-wrench text-[10px]"></i> مسؤول
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600">
                                    <i class="fa-solid fa-user text-[10px]"></i> عميل
                                </span>
                            @endif
                        </td>

                        {{-- Change Role Button --}}
                        <td class="p-5 text-center">
                            @if($user->id === auth()->id())
                                <span class="text-xs text-gray-300 font-bold">—</span>
                            @else
                                <form action="{{ route('admin.customers.role', $user->id) }}" method="POST"
                                      onsubmit="return confirm('هل أنت متأكد من تغيير دور «{{ addslashes($user->name) }}»؟')">
                                    @csrf
                                    <input type="hidden" name="role" value="{{ $user->isAdmin() ? 'customer' : 'admin' }}">
                                    <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer
                                                   {{ $user->isAdmin()
                                                       ? 'bg-gray-100 hover:bg-gray-200 text-gray-700'
                                                       : 'bg-primary/10 hover:bg-primary/20 text-primary' }}">
                                        @if($user->isAdmin())
                                            <i class="fa-solid fa-user-minus text-[10px]"></i> تحويل لعميل
                                        @else
                                            <i class="fa-solid fa-user-shield text-[10px]"></i> ترقية لمسؤول
                                        @endif
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-14 text-center text-gray-400 font-bold">
                            <div class="flex flex-col items-center gap-3">
                                <span class="text-5xl">👤</span>
                                <span>لا يوجد مستخدمون مطابقون للبحث.</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="p-5 border-t border-gray-50 flex items-center justify-between">
            <p class="text-xs text-gray-400 font-semibold">
                عرض {{ $users->firstItem() }}–{{ $users->lastItem() }} من أصل {{ $users->total() }} مستخدم
            </p>
            {{ $users->links() }}
        </div>
    </div>

</div>
@endsection
