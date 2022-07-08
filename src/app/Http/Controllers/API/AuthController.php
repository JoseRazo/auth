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
    public function login(Request $request)
    {
        $user = User::where('login', $request->login)->first();

        $encryptPassword = Crypt::encryptString($request->password);
        $decryptPassword = Crypt::decryptString($encryptPassword);

        if ($user && $decryptPassword === $user->password && $user->activo) {
            $success['token'] =  $user->createToken($user->password)->plainTextToken;
            $success['login'] =  $user->login;
            return $this->sendResponse($success, 'Usuario autenticado.');
        } else {
            return $this->sendError('Error', ['error' => 'Unauthorised']);
        }
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return $this->sendResponse([], 'Sesión cerrada correctamente.');
    }

    // public function login(Request $request)
    // {
    //     $user = User::where('login', $request->login)->first();
    //     $encryptPassword = Crypt::encryptString($request->password);
    //     $decryptPassword = Crypt::decryptString($encryptPassword);
    //     $credentials = $request->only('login', $decryptPassword);


    //     try {
    //         if ($user && $decryptPassword === $user->password && $user->activo) {
    //             // $user = auth()->user();
    //             // $data['token'] = JWTAuth::fromUser($user);
    //             $data = [
    //                 'token' => JWTAuth::fromUser($user),
    //                 'login' => $user->login,
    //             ];

    //             $response['data'] = $data;
    //             $response['status'] = 1;
    //             $response['code'] = 200;
    //             $response['message'] = 'Usuario autenticado';
    //             return response()->json($response, 200);
    //         }
    //     } catch (JWTExcetions $e) {
    //         $response['data'] = null;
    //         $response['code'] = 500;
    //         $response['message'] = 'Error al crear el token';
    //         return response()->json($response, 500);
    //     }

    //     $response['status'] = 0;
    //     $response['code'] = 401;
    //     $response['data'] = null;
    //     $response['message'] = 'El nombre de usuario o contraseña son incorrectos';
    //     return response()->json($response, 401);
    // }

    // public function logout(Request $request)
    // {
    //     // Get JWT Token from the request header key "Authorization"
    //     $token = $request->header("Authorization");
    //     // Invalidate the token
    //     try {
    //         JWTAuth::invalidate(JWTAuth::getToken());
    //         return response()->json([
    //             "status" => "success",
    //             "message" => "Sesión cerrada correctamente."
    //         ]);
    //     } catch (JWTException $e) {
    //         // something went wrong whilst attempting to encode the token
    //         return response()->json([
    //             "status" => "error",
    //             "message" => "Error, por favor intente nuevamente."
    //         ], 500);
    //     }
    // }
}
