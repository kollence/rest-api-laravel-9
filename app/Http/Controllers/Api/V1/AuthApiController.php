<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\V1\LoginRequest;
use App\Http\Requests\V1\RegisterRequest;
use App\Http\Resources\V1\UserResource;

class AuthApiController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try{
            $request['password'] = bcrypt($request['password']);
            $user = User::create(array_merge($request->all(), ['role_id' => 2]));
    
            $token = $user->createToken('user-token')->plainTextToken;
            $user->token = $token;
            return new UserResource($user);
        }catch(\Exception $e){
            return response()->json(['err' => $e->getMessage()]);
        }
    }

    public function login(LoginRequest $request)
    {
        try{

            $user = User::where('email', $request->email)->first();

            if(!$user || !Hash::check($request->password, $user->password)){
                return response(['message' => 'Bad enteries'], 401);
            }

            if($user->role_id == 1){
                $token = $user->createToken('admin-token')->plainTextToken;
            }else{
                $token = $user->createToken('user-token')->plainTextToken;
            }
            $user->token = $token;
            return new UserResource($user);
        }catch(\Exception $e){
            return response()->json(['err' => $e->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
        try {
            auth()->user()->tokens()->delete();

            return ['message' => 'logged out'];
        } catch (\Exception $e) {
            return ['err' => $e->getMessage()];
        }
        
    }
}
