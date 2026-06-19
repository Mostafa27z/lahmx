<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
   

    /**
     * Show the profile edit form
     */
    public function show()
    {
        return view('front.profile.show', [
            'user' => auth()->user(),
        ]);
    }

    /**
     * Update user profile information
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => ['required', 'string', 'max:20'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'name.required' => 'الاسم مطلوب.',
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email' => 'يجب إدخال بريد إلكتروني صحيح.',
            'email.unique' => 'هذا البريد الإلكتروني مسجل مسبقاً.',
            'phone.required' => 'رقم الجوال مطلوب.',
            'avatar.image' => 'يجب أن يكون الملف المرفوع صورة.',
            'avatar.mimes' => 'يجب أن تكون الصورة بصيغة: jpeg, png, jpg, webp.',
            'avatar.max' => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت.',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        $user->save();

        return back()->with('success', 'تم تحديث بيانات حسابك بنجاح.');
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'current_password' => [
                'required',
                'string',
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail('كلمة المرور الحالية غير صحيحة.');
                    }
                },
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',           // At least one lowercase letter
                'regex:/[A-Z]/',           // At least one uppercase letter
                'regex:/[0-9]/',           // At least one digit
                'regex:/[@$!%*?&]/',       // At least one special character
                'confirmed',
            ],
        ], [
            'current_password.required' => 'كلمة المرور الحالية مطلوبة.',
            'password.required' => 'كلمة المرور الجديدة مطلوبة.',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل.',
            'password.regex' => 'كلمة المرور يجب أن تحتوي على أحرف كبيرة وصغيرة وأرقام وأحرف خاصة.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'تم تحديث كلمة المرور بنجاح.');
    }

    /**
     * Delete user account
     */
    public function destroy(Request $request)
    {
        $user = auth()->user();

        // Delete user's cart
        $user->cart()->delete();

        // Delete user's orders (optional: you can also soft delete)
        $user->orders()->delete();

        // Delete the user
        $user->delete();

        // Logout
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'تم حذف حسابك بنجاح.');
    }
}