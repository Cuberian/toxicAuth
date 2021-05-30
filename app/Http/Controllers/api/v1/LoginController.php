<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login( Request $request )
    {
        $login = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        if( !Auth::attempt( $login ) )
        {
            return response(['message' => 'fail']);
        }

        $accessToken = Auth::user()->createToken('authToken')->accessToken;

        return response(['passport_token' => $accessToken]);
    }

    public function register( Request $request ) {
        $validatedData = $request->validate([
            'username' => 'required',
            'email' => 'email|required|unique:users',
            'password' => 'required'
        ]);

        $validatedData['password'] = bcrypt($request->password);

        $user = User::create($validatedData);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response(['passport_token' => $accessToken]);
    }

    public function getTokenUser( Request $request ) {
        return $request->user();
    }
}
