<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Model
use App\Models\User;

// Request
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;

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

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->input('email'))->first();

        if(!$user){
            return $this->responseFailed(404, 'User tidak ditemukan');
        }else if(empty($user->email_verified_at)){
            return $this->responseFailed(401, 'User tidak aktif, silahkan lakukan verifikasi email');
        }else if(!\Hash::check($request->input('password'), $user->password)){
            return $this->responseFailed(401, 'Password Salah!');
        }

        $token = auth()->attempt(request(['email', 'password']));
        return $this->respondWithToken($token);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return $this->responseSuccess(200, 'Login Success', [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
