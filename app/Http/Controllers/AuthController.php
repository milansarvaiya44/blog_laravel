<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseHelper;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::errorResponse($validator->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        // return response()->json();

         return ResponseHelper::successResponse([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ],'user register successfully');
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        /*return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);*/

         return ResponseHelper::successResponse([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ],'user login successfully');
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();

        return response()->json(['error' => false,'message' => 'Successfully logged out']);
    }
}
