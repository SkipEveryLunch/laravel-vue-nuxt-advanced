<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateInfoRequest;
use App\Http\Requests\UpdatePasswordRequest;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(RegisterRequest $req){
        $user = User::create($req->only('first_name','last_name','email')+[
            'password'=>Hash::make($req->input('password')),
            "is_admin"=>1
        ]);
        return response($user,Response::HTTP_CREATED);
    }
    public function login(Request $req){
        if(!Auth::attempt($req->only('email','password'))){
            return response([
                'error'=>'invalid credentials'
            ],Response::HTTP_UNAUTHORIZED);
        };
        $user = Auth::user();
        $jwt = $user->createToken('token',['admin'])->plainTextToken;
        $cookie = cookie("jwt",$jwt,60 * 24);
        return response([
            "message"=>"success"
        ])->withCookie($cookie);
    }
    public function user(Request $req){
        return $req->user();
    }
    public function logout(){
        $cookie = \Cookie::forget("jwt");
        return response([
            "message"=>"success"
        ])->withCookie($cookie);
    }
    public function updateInfo(UpdateInfoRequest $req){
        $user = $req->user();
        $user->update(
            $req->only(
                'email',
                'first_name',
                'last_name',
            )
        );
        return response($user,Response::HTTP_ACCEPTED);
    }
    public function updatePassword(UpdatePasswordRequest $req){
        $user = $req->user();
        $user->update([
            "password"=>Hash::make($req->input("password"))
        ]);
    }
    
}
