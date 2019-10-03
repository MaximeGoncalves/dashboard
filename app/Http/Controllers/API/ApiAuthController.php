<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiAuthController extends Controller
{
    public function login(Request $request){
        $loginData = $request->validate([
            'name' => 'required',
            'password' => 'required'
        ]);

        if(!auth()->attempt($loginData)){
            abort(403, 'Invalid credential');
        }
        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response(['user' => auth()->user(), 'accessToken'=> $accessToken]);
    }
}
