<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ResponseTrait;

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $data = [
            'token' => $user->createToken('authToken')->accessToken,
            'user' => $user
        ];

        return $this->success('Register successful.', $data, 200);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();

        $data = [
            'token' => $user->createToken('authToken')->accessToken,
            'user' => $user
        ];

        return $this->success('Login successful.', $data, 200);
    }

    public function user(Request $request)
    {
        return $this->success('User return successfully', $request->user(), 200);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return $this->success('Logged out successfully.', 200);
    }
}
