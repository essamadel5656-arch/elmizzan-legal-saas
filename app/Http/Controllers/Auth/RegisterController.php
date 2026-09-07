<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller — SELF-REGISTRATION DISABLED
    |--------------------------------------------------------------------------
    |
    | Self-registration is DISABLED. Only the Admin may create user accounts
    | via the Lawyer management panel (/lawyers/create).
    | Both the GET form and POST endpoints are hard-blocked below.
    |
    */

    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Block the registration form — returns 404 to not expose the endpoint.
     */
    public function showRegistrationForm()
    {
        abort(404);
    }

    /**
     * Block direct POST requests to /register.
     * Prevents crafted requests that bypass the disabled UI route.
     */
    public function register(Request $request)
    {
        abort(404);
    }

    /**
     * Kept for interface compliance only — never reached.
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Kept for interface compliance only — never reached.
     */
    protected function create(array $data)
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
