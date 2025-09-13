<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;

class LoginController extends Controller
{

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

      

      return  $this->authService->attemptLogin($request);


    }

    

    


    
}