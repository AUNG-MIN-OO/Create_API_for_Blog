<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    ##create register method
    public function register(Request $request){
        ##validate name, email, password
        $request->validate(
            [
                'name' => 'required|string|max:100',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8|max:20'
            ]
        );

        ##save to user and return response
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $user->save();

        $token = $user->createToken('API_Blog_Token')->accessToken;

        return ResponseHelper::success([
            'access_token' => $token
        ]);
    }

    ##login
    public function login(Request $request){
        ##login validation
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $user = auth()->user();
            $token = $user->createToken('API_Blog_Token')->accessToken;
            return ResponseHelper::success([
                'token' => $token
            ]);
        }
    }
}
