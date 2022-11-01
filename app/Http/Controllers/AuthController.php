<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        
        
            
            if($request->otp_login){

                // $user=User::where('email',$request->email)->first();

                $user_id=$request->user_id;

                if($user!=null){
                    if (!Auth::loginUsingId($user_id)) {
                        return response()->json([
                            'message' => 'Invalid Login Details.'
                        ], 401);
                    }
                }else{
            
                    return response()->json([
                        'message' => 'Account Not Found.'
                    ], 401);
        
                }

            }else{

                if (!Auth::attempt($request->only('email', 'password'))) {
                    return response()->json([
                        'message' => 'Invalid login details'
                    ], 401);
                }
            }

        
        
        $request->session()->regenerate();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    public function me(Request $request)
    {
        return response()->json([
            'data' => $request->user(),
        ]);
    }
}
