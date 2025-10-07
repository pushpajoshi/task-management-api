<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;



class AuthController extends Controller
{
    //register
     public function register(RegisterRequest $request){
        $data = $request->validated();
        $data['role'] = $request->role ?? 'user';
        $user = User::create([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'password'=>Hash::make($data['password']),
            'role'=>$data['role'],
        ]);
        Auth::loginUsingId($user->id);
        $token = $user->createToken('api-token')->plainTextToken;
        $user = User::SimpleDetails()->where('id',$user->id)->first();
        $user->token = $token;
        return send_response(201, __('api.suc_user_register'), $user);   
     }

    //login
    public function login(Request $request)
    {
        $request->validate(['email'=>'required|email|exists:users','password'=>'required']);

        $user = User::where('email',$request->email)->whereNull('deleted_at')->first();
         if(!$user || !Hash::check($request->password,$user->password)){
            return response()->json(['status'=>'error','message'=>'Invalid credentials'],401);
        }
        $attempt = ['email' => $request->email, 'password' => $request->password];
        if (Auth::attempt($attempt)) {
            $token = $user->createToken('api-token')->plainTextToken;
            $user = User::SimpleDetails()->where('id',$user->id)->first();
            $user->token = $token;
            return send_response(200, __('api.suc_user_login'), $user);   
        }
    }

    //logout
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
            return send_response(200, __('api.suc_user_logout'));   
    }


}
