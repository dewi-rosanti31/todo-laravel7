<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

class AuthController extends Controller
{
    public function register (Request $request){ 
    
        $validateData = $request->validate([
            'name'=>'required|max:25',
            'email'=>'email|required|unique:users',
            'password'=>'required|confirmed'
        ]);

        // craete user
        $user = new user([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
        ]);
    
        $user->save();
    
        return response()->json($user,201);
    }

    public function login (Request $request){
    
        $validateData = $request->validate([
            'email'=>'email|required',
            'password'=>'required'
        ]);

        $login_detail = (['email','password']);

       if(!Auth :: attempt($login_detail)){
        return response()->json([
            'error' => 'login failed. Please check your login detail'
            ]. 401);

        $user = $request -> user();

        $tokenResult = $user->createToken('AccessToken');
        $token = $tokenResult->token;
        $token->save();

       }
    
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_id' => $tokenResult->id,
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]. 201);
    }
}