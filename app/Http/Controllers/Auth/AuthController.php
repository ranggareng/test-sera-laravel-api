<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Model
use App\Models\User;

// Request
use App\Http\Requests\Auth\RegisterRequest;

// Resource
use App\Http\Resources\UserResource;

// Event
use App\Events\UserCreatedEvent;

class AuthController extends Controller
{
    function register(RegisterRequest $request){
        $user = User::create([
            'email' => $request->input('email'),
            'password' => \Hash::make($request->input('password'))
        ]);

        if($user){
            event(new UserCreatedEvent($user));
            return $this->responseSuccess(200, 'User created successfully', new UserResource($user));
        }else{
            return $this->responseFailed(500, 'Opps.. Something went wrong!');
        }
    }
}
