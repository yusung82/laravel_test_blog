<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    //user add
    public function signUp(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:4',
            'password_confimation' => 'required|same:password',
        ]);
        //폼검증 실패일경우
        if($validator->fails()){
            return response()->json([
                'message' => '폼 검증실패',
                'errors' => $validator->errors()], 422);
        }

        $params = $request->only(['name', 'email', 'password']);
        $params['password'] = bcrypt( $params['password']);
        $user = User::create($params);


//        $name = $request->input('name');
//        $email = $request->input('email');
//        $password = $request->input('password');

        return response()->json($user);
    }

    //log in
    public function signIn(Request $request){
        $params = $request->only(['email', 'password']);

        //사용자 확인 - email / password
        if( Auth::attempt($params) ){
            $user = User::where('email', $params['email'] )->first();
            $token = $user->createToken( env('APP_KEY' ));

            return response()->json([
                'user' => $user,
                'token' => $token->plainTextToken,
            ]);

        }else{
            return response()->json( ['message' => '로그인정보를 확인하세요.'], 400 );
        }
    }


}
