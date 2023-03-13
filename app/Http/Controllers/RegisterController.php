<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;

class RegisterController extends Controller
{
    /**
     * Display register page.
     * @test test_register_get_page
     */
    public function show()
    {
        return view('auth.register');
    }

    /**
     * Handle account registration request
     * @param RegisterRequest $request
     * @test test_it_register_new_user
     * @test test_it_register_new_user_with_error_password_confirmation
     * @test test_it_register_new_user_with_error_email
     * 
     */
    public function register(RegisterRequest $request) 
    {
        $user = User::create($request->validated());

        auth()->login($user);

        return redirect('/')->with('success', "Account successfully registered.");
    }
}
