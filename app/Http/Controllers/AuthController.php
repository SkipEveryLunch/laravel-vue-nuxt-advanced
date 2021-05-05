<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\RegisterRequest;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(RegisterRequest $req){
        $user = User::create($req->only('first_name','last_name','email')+[
            'password'=>\Hash::make($req->input('password')),
            "is_admin"=>1
        ]);
        return response($user,Response::HTTP_CREATED);
    }
}
