<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class AuthenticationController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'email'=>'required|email',
            'password'=>'required',
        ]);

        $user=User::where('email',$request->email)->first();
    
    //dd($user);
    if ( !$user || ! Hash::check($request->password, $user->password)){
        throw ValidationException::withMessages([
            'account' => ['the provoided credentials are incorrect'],
        ]);
    }

    return $user->createToken('user login')->plainTextToken;
    }

    //membuat function logout

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete;

        return response()->json([
            'massage'=>'anda telah keluar'
        ]);
    }

    public function me(){
        $user = Auth::User();
        return response()->json($user);
    }
}
