<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function register(Request $req)
    {

        $ValidateData = Validator::make($req->all(), [
            'name' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'address' => 'required',
            'password' => 'required|confirmed'
        ]);
        if ($ValidateData->fails()) {

            return response()->json(['status' => 'error', 'error' => $ValidateData->errors()], 404);
        } else {
            $ValidateData['password'] = bcrypt($ValidateData['password']);
            $user = User::create($ValidateData);
            $accessToken = $user->createToken('authToken')->accessToken;
            return response(['user' => $user, 'accessToken' => $accessToken]);
        }
    }

    public function login(Request $request)
    {

        $val = $request->only(['email', 'password']);
        if (!Auth::attempt($val)) {
            return response(['message' => 'INvalid credentials'], 404);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;
        return response(['user' => auth()->user(), 'accessToken' => $accessToken]);
    }

    public function user()
    {
        return Auth::user();
    }
}
