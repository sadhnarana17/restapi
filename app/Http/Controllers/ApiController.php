<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\User;
use Hash;


class ApiController extends Controller
{

      public function register(Request $request)
    {        
    	
    	$input = $request->all();
    	$input['password'] = Hash::make($input['password']);
    	User::create($input);
        return response()->json(['result'=>true]);
    }
    
    public function login(Request $request)
    {
    	        $credentials = $request->only('email', 'password');
        try {
            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        $access_token=compact('token');
      
        // if no errors are encountered we can return a JWT
        //->header("Authorization": "Bearer"." ".compact('token')."")
        return response()->json($access_token)->header('Content-Type','application/json')->header("Authorization","Bearer"." ".$access_token['token']."");
    }
    
    public function get_user_details(Request $request)
    {
    	$input = $request->all();
    	$user = JWTAuth::toUser($input['token']);
      if(isset($user)&& !empty($user))
      {
      	   $userss=User::all();
      	     return response()->json(['result' => $userss])->header('Content-Type','application/json')->header("Authorization","Bearer"." ".$input['token']."");
      }
      else
      {
      	 return response()->json(['result' =>'failed'],'201');
      }
      
        //return response()->json(['result' => $user]);
    }
}
