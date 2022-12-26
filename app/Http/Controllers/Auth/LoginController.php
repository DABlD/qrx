<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);
    
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if(auth()->user()->email_verified_at == null){
                $key = auth()->user()->email;

                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'validate' => 'Your email is not yet verified.',
                    'key' => $key
                ]);
            }
    
            return redirect()->route('dashboard');
        }
    
        return back()->withErrors([
            'auth' => 'Invalid Username/Password.',
        ]);
    }
}