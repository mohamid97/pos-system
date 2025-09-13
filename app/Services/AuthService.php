<?php
namespace App\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class AuthService{


    protected $redirectTo = '/dashboard';

    protected function credentials(Request $request)
    {
        return $request->only('email', 'password');
    }



    public function attemptLogin(Request $request)
    {
        $credentials = $this->credentials($request);

       
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            return redirect()->intended($this->redirectTo); // or wherever
        }
    }


}