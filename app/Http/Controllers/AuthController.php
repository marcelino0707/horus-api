<?php

namespace App\Http\Controllers;

use App\Models\PersonalRefreshToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }


    public function login()
    {
        $validator = Validator::make(request()->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray(); 
            $message = reset($errors)[0];
            return response()->json(["message" => $message], 400);
        }

        $credentials = request(['username', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $expiry = now()->addMinutes(config('jwt.refresh_ttl'));
        PersonalRefreshToken::create([
            'user_id' => auth()->user()->id, // user_id as id
            'refresh_token' => $token,
            'expired_at' => $expiry
        ]);

        // Create refresh token cookie (HTTP-only)
        $refreshTokenCookie = cookie('refresh_token', $token, config('jwt.refresh_ttl'), null, null, false, true);


        return response()->json([
            'message' => 'Login successful',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL()
        ])
        ->withCookie($refreshTokenCookie);
    }

    public function logout()
    {
        $user = auth()->user();

        PersonalRefreshToken::where('user_id', $user->id)->delete();

        $refreshTokenCookie = Cookie::forget('refresh_token');

        auth()->logout();

        return response()->json(['message' => 'Successfully logged out'], 200)->withCookie($refreshTokenCookie);;
    }

    public function refresh()
    {
        $refreshToken = request()->cookie('refresh_token');

        $refreshTokenData = PersonalRefreshToken::where('refresh_token', $refreshToken)->first();

        if (!$refreshTokenData) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $token = auth()->refresh();
        $expiry = now()->addMinutes(config('jwt.refresh_ttl')); 
        $refreshTokenData->update([
            'refresh_token' => $token,
            'expired_at' => $expiry
        ]);

        // Create refresh token cookie (HTTP-only)
        $refreshTokenCookie = cookie('refresh_token', $token, config('jwt.refresh_ttl'), null, null, false, true);
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL()
        ])
        ->withCookie($refreshTokenCookie);
    }

    public function register(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'username' => 'required|unique:users,username',
            'nama' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray(); 
            $message = reset($errors)[0];
            return response()->json(["message" => $message], 400);
        }

        $member = User::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tanggal_daftar' => now(),
        ]);

        if ($member) {
            $response['message'] = 'Member has been successfully registered.';
            $response['data'] = array(
                'nama' => $member->nama,
                'email' => $member->email,
            );

            return response()->json($response, 201);
        } else {
            $response['message'] = 'Member registration failed.';
            return response()->json($response, 500);
        }
    }
}
