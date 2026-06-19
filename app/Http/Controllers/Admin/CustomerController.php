<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search  = $request->input('search');
        $role    = $request->input('role', 'all');

        $query = User::query();

        if ($role !== 'all') {
            $query->where('role', $role);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users        = $query->latest()->paginate(15)->withQueryString();
        $totalUsers   = User::count();
        $totalAdmins  = User::where('role', 'admin')->count();
        $totalCustomers = User::where('role', 'customer')->count();

        return view('admin.customers.index', compact('users', 'search', 'role', 'totalUsers', 'totalAdmins', 'totalCustomers'));
    }

    public function updateRole(Request $request, User $user)
    {
        // Prevent demoting yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'لا يمكنك تغيير دورك بنفسك.');
        }

        $request->validate([
            'role' => 'required|in:admin,customer',
        ]);

        $user->update(['role' => $request->role]);

        $label = $request->role === 'admin' ? 'مسؤول' : 'عميل';
        return back()->with('success', "تم تحديث دور المستخدم «{$user->name}» إلى {$label} بنجاح.");
    }
}
