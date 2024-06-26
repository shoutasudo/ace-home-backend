<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
        ]);

        $json = [
            'data' => $user
        ];

        return response()->json($json, Response::HTTP_OK);
    }


    public function login(LoginRequest $request)
    {
        $attributes = $request->only(['email', 'password']);
        if (Auth::attempt($attributes)) {
            $user = User::whereEmail($request->email)->first();

            $token = $user->createToken("login:user{$user->id}")->plainTextToken;

            return response()->json([
                'user' => new UserResource($user),
                'access_token' => $token
            ]);
        }
        return response()->json('認証情報が誤っています', Response::HTTP_UNAUTHORIZED);
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();

        $cookie = cookie()->forget('token');

        return response()->json([
            'message' => 'ログアウトしました'
        ])->withCookie($cookie);
    }

    // ユーザー情報取得
    public function user(Request $request) {
        return new UserResource($request->user());
    }
}
