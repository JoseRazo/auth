<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends BaseController
{
    // public function login(Request $request)
    // {
    //     $user = User::where('login', $request->login)->first();

    //     $encryptPassword = Crypt::encryptString($request->password);
    //     $decryptPassword = Crypt::decryptString($encryptPassword);

    //     if ($user && $decryptPassword === $user->password && $user->activo) {
    //         $success['token'] =  $user->createToken($user->password)->plainTextToken;
    //         $success['login'] =  $user->login;
    //         return $this->sendResponse($success, 'User signed in');
    //     } else {
    //         return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
    //     }
    // }

    // public function logout(){
    //     auth()->user()->tokens()->delete();
    //     return $this->sendResponse([], 'User signed out');
    // }

    public function login(Request $request)
    {
        $user = User::where('login', $request->login)->first();
        $encryptPassword = Crypt::encryptString($request->password);
        $decryptPassword = Crypt::decryptString($encryptPassword);
        $credentials = $request->only('login', $decryptPassword);


        try {
            if ($user && $decryptPassword === $user->password && $user->activo) {
                // $user = auth()->user();
                $data['token'] = JWTAuth::fromUser($user);

                $response['data'] = $data;
                $response['status'] = 1;
                $response['code'] = 200;
                $response['message'] = 'Usuario autenticado';
                return response()->json($response, 200);
            }
        } catch (JWTExcetions $e) {
            $response['data'] = null;
            $response['code'] = 500;
            $response['message'] = 'Error al crear el token';
            return response()->json($response, 500);
        }

        $response['status'] = 0;
        $response['code'] = 401;
        $response['data'] = null;
        $response['message'] = 'El nombre de usuario o contraseña son incorrectos';
        return response()->json($response, 401);
    }
}
