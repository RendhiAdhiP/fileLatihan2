<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:5',
        ]);

        if ($validate->fails()) return response()->json(['errors' => $validate->errors()], 401);

        $credentials = $request->only(['email', 'password']);

        if (Auth::attempt($credentials)) {

            /** @var \App\Models\User */
            $user = auth()->user();
            $token = $user->createToken('authToken')->plainTextToken;

            $data = [
                'user' => $user->name,
                'email' => $user->email,
                'access_token' => $token,
            ];
            return response()->json(['message' => 'login success', 'user' => $data], 200);
        }

        return response()->json(['message' => 'login failed'], 400);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'logout success'], 200);
    }
}
