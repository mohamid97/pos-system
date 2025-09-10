<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;


class LoginController extends Controller
{
    protected $redirectTo = '/';
    protected $authService;

    public function __construct(AuthService $authService)
    {
       $this->authService = $authService;
    }
    public function showLoginForm()
    {
        return view('auth.login');
    }


    public function login(LoginRequest $request)
    {

        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->authService->hasTooManyLoginAttempts($request)) {
            return $this->authService->sendLockoutResponse($request);
        }

        // Attempt to log the user in
        if ($this->authService->attemptLogin($request)) {
            return $this->authService->sendLoginResponse($request);
        }

        // If login attempt was unsuccessful
        $this->authService->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    

    


    
}