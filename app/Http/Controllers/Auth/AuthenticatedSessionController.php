<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache; 

use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Str;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = $request->user(); 

        if ($user->role == 'ems') {
            $token = Str::random(64);

            DB::table('sso_tokens')->insert([
                'token' => $token,
                'user_id' => $user->id,
                'expires_at' => now()->addMinutes(1),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect("http://cpmcare.banyongservice.com/sso-login?token={$token}");

        } elseif ($user->role === 'admin') {
            return redirect()->route('admin.complaints.index');
        }

        // กรณีสุดท้าย (else) ไม่ต้องใส่ else ก็ได้ ปล่อยไหลมาตรงนี้เลย
        return redirect()->route('complaints.history');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
