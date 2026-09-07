<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    protected function redirectTo()
    {
        if (auth()->user()->role === 'admin') {
            return '/home';
        }
        return '/home';
    }
}