<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function submit(Request $request){
        //validate phone num
        $request->validate([
            'phone' => 'required|numeric|min:10'
        ]);

        //find or create user model
        $user = User::firstOrCreate([
            'phone' => $request->phone
        ]);

        if(!$user){
            return response()->json(['message' => 'Could not process a user with that phone number. '], 401);
        };

        //send the user an otp
        $user->notify(new LoginNeedsVerification());

        //return back a response
        return response()->json(['message' => 'Text message notificaation sent. ']);
    }

    public function verify(Request $request){
        //validate incoming request
        $request->validate([
            'phone' => 'required|numeric|min:10',
            'login_code' => 'required|numeric|between:111111,999999'
        ]);

        //find user
        $user = User::where('phone', $request->phone)
                    ->where('login_code', $request->login_code)
                    ->first();

        //is code provided match with the one saved?
        //if yes, return auth token
        if($user){
            $user->update([
                'login_code' => null
            ]);
            return $user->createToken($request->login_code)->plainTextToken;
        }

        //if no , return login with a message
        return response()->json(['message' => 'Invalid verification code.'], 401);
    }
}
