<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Http\Resources\UserResource;

class UserController extends BaseController
{
    public function index()
    {
        $users = User::all();
        return $this->sendResponse(UserResource::collection($users), 'Users fetched.');
    }

    public function show($id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return $this->sendError('User does not exist.');
        }
        return $this->sendResponse(new UserResource($user), 'User fetched.');
    }
}
