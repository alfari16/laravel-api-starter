<?php

namespace App\Http\Controllers\Api;

use Auth;
use JWTAuth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthenticateController extends Controller
{
    public function authenticate(Request $request)
    {
        if ($request->has(['username', 'email', 'password'])) {
            $credentials = $request->only('email', 'password');
        }elseif ($request->has(['username', 'password'])) {
            $credentials = $request->only('username', 'password');
        }elseif ($request->has(['email', 'password'])) {
            $credentials = $request->only('email', 'password');
        }

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json([
            'user' => Auth::user(),
            'token' => compact('token'),
            'status_code' => 200,
        ], 200);
    }
}
