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
            // $user = User::create(array_merge($request->all(), ['role_id' => 2]));
            $user = User::create($request->all());
            $user->roles()->attach([2]);
            // return $user->load('roles');
            $token = $user->createToken('user-token',['none'])->plainTextToken;
            $user->token = $token;
            return new UserResource($user->load('roles'));
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
            foreach($user->roles as $role){
                if($role->role == 'admin' ){
                    $token = $user->createToken('admin-token',['create','update','delete'])->plainTextToken;
                    $user->token = $token;
                // }else if($role->role == 'editor'){
                //     $token = $user->createToken('editor-token',['create','update'])->plainTextToken;
                //     $user->token = $token;
                }else{
                    $token = $user->createToken('user-token',['none'])->plainTextToken;
                    $user->token = $token;
                }
            }

            return new UserResource($user);
            // return new UserResource($user->with('roles'));
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
