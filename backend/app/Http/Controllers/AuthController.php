<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    //

    public function login(AuthRequest $request){
        try {
            //code...
            //get credentials from request
            $credentials = $request->only('email', 'password');

            //if auth failed
            if(!$token = auth()->guard('api')->attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => __('auth.failed')
                ], 401);
            }

            Log::info('Login the Customer: ', ['customer' => auth()->guard('api')->user()]);

             //if auth success
            return response()->json([
                'success' => true,
                'message' => __('validation.success_json'),
                'data'    => auth()->guard('api')->user(),
                'token'   => $token
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Login Customer error: ', ['customer' => $th->getMessage()]);
            return response()->json([
                'success' => false,
                'message'   => $th->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        //remove token
        try {
            //code...
            $removeToken = auth()->guard('api')->invalidate(auth()->guard('api')->getToken());
            if ($removeToken) {
                Log::info('Logout the Customer: ', ['customer' => auth()->guard('api')->user()]);
                //return response JSON
                return response()->json([
                    'status' => true,
                    'message' => __('validation.success_json'),
                ], 204);
            }
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Logout Customer error: ', ['customer' => $th->getMessage()]);
            return response()->json([
                'success' => false,
                'message'   => $th->getMessage()
            ], 500);
        }
    }
}
