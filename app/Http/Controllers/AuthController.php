<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
class AuthController extends Controller
{
    public $loginAfterSignup=false;
    public function getUser()
    {
           return  JWTAuth::parseToken()->authenticate();
    }
    public function myProfile()
    {
        return $this->getUser();
    }
    public function setMyProfile(Request $request)
    {
        $user=User::find($this->getUser()->id);
        $user->name=$request->name;
        $user->email=$request->email;
        $user->save();
        return $user;
    }
    public function login(Request $request)
    {
        //dd($request->all());
        $credentials=$request->only('email','password');
        $token=null;
        if(!$token=JWTAuth::attempt($credentials))
        {
            return response()->json([
                "status"=>false,
                "message"=> 'unauthorized'
            ]);
        }
        return response()->json([
            "status"=>true,
            "token"=>$token
        ]);
        
    }
    public function register(Request $request)
    {
        $this->validate($request,[
            "name"=>"required|string",
            "email"=>"required|email",
            "password"=>"required|string|min:6",
        ]);
        $user=new User();
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=bcrypt($request->password);
        $user->save();
        if($this->loginAfterSignup)
        {
            return $this.login($request);
        }
        return response()->json([
            "status"=>true,
            "user"=>$user
        ]);
    }
    public function logout(Request $request)
    {
        //return $request->token;
        //return JWTAuth::parseToken()->authenticate()->name;
        $this->validate($request,[
            "token"=>"required"
        ]);
        try{
            JWTAuth::invalidate($request->token);
            return response()->json([
                "status"=>true,
                "message"=>"user logged successfully"
            ]);
        }
        catch(JWTException $e)
        {
            return response()->json([
                "status"=>false,
                "message"=>"there was a problem"
            ]);
        }
    }
}
