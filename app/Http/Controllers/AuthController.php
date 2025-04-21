<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=>'required|string|max:255',
            'email'=>'required|string|email|max:255|unique:users',
            'password'=>'required|string|max:255'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $user = User::create([
            "name"=> $request->name,
            "email"=>$request->email,
            "password" => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;


        return response()->json(['data'=>$user,'access_token'=>$token, 'token_type'=>'Bearer'],);
    }

    public function login(Request $request){
        
        $credentials = $request->only('email', 'password');
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Credenciales incorrectas',
            ], 401);
        }

        $user = User::where("email",$request["email"])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(["message"=>"Login correcto",
                                "accessToken"=>$token, 
                                "token_type"=>"Bearer",
                                 "user"=>$user]);
      
    }

    public function logout(){
        
        $user = auth()->user();

        $cacheKey = 'tasks_user_' . $user->id;
        Cache::forget($cacheKey);

        auth()->user()->tokens()->delete();
        return ['message'=>'Todos los tokens han sido liminados'];
    }
}
