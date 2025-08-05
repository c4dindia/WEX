<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

use function PHPUnit\Framework\isEmpty;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        if (Auth::check())
        {
            if (auth()->user()->is_admin == '1') {
                return redirect()->route('showWallesterDashboard');
            }
            else{
                return redirect()->route('showClientDashboard');
            }
        }
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $user = User::where('email',$request->email)->first();
        if(!$user)
        {
            return back()->with('error','Account not Acivated by Admin');
        }
        if($user != isEmpty() && $user->is_admin == '1')
        {
            //
            $request->authenticate();

            $request->session()->regenerate();

            return redirect()->intended(RouteServiceProvider::HOME);
        }
        else
        {
            return back()->with('error','Account Accessible by Admin Only');
        }

    }

    public function storeclient(LoginRequest $request): RedirectResponse
    {
        $user = User::where('email',$request->email)->first();
        if(!$user)
        {
            return back()->with('error','Account not Registered');
        }

        if($user != isEmpty() && $user->status == '1')
        {
            $request->authenticate();

            $request->session()->regenerate();
            return redirect('/client-dashboard');
            // return redirect()->intended(RouteServiceProvider::CLIENTHOME);
        }
        else
        {
            return back()->with('error','Account not Acivated by Admin');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        if(Auth::user()->is_admin == 3)
        {
            Auth::guard('web')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect('/client-login');
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
