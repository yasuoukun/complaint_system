<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            // เปลี่ยน validation จาก name เป็น 3 ตัวใหม่
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:10'],
            
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            // บันทึกลง Database
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'complainant', // กำหนดให้เป็นประชาชนทั่วไป
        ]);

        event(new Registered($user));

        Auth::login($user);

        // ถ้าเป็น Admin ไปหน้า Admin ถ้าคนธรรมดาไปหน้าประวัติ
        if ($user->role === 'admin') {
            return redirect()->route('admin.complaints.index');
        }
        return redirect()->route('complaints.history');
    }
}
