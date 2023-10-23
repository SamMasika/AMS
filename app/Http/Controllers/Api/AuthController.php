<?php
namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function createUser(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make($request->all(), 
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
 
    public function login(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
                'remember_me' => 'boolean'
            ]);
    
            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
    
            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password do not match our records.',
                ], 401);
            }
    
            $user = $request->user();
    
            // Set the expiration time for the token, e.g., 1 hour (3600 seconds)
            $expiration = now()->addSeconds(3600); // You can adjust the time as needed
    
            // Create a token with an expiration date
            $token = $user->createToken('API_TOKEN', ['expires_at' => $expiration]);
            return response()->json([
                'success' => true,
                'status' => 300,
                'message' => 'You are logged in!',
                'access_token' => $token->plainTextToken,
                'token_type' => 'Bearer',
                'expires_at' => $expiration->format('Y-m-d H:i:s'), // Include the expiration date in the response
                'user' => [
                    'name' => $user->name,
                    'username' => $user->username,
                    'phone' => $user->phone,
                    'last_login' => $user->last_sign_in_at->toDateTimeString(),
                    // 'permissions' => $permissions
                ]
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    
     
    public function logout(Request $request)
    {
        $user = Auth::user();
        $user->tokens()->delete(); // Revoke all user's tokens

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }
         public function user(Request $request){
             $user = $request->user();
             $permissions = $user->getAllPermissions()->pluck('name');
             
         
             return $this->sendResponse([
                 
                     'fname' => $user->fname,
                     'lname' => $user->lname,
                     'username' => $user->username,
                     'email' => $user->email,
                     'phone' => $user->phone,
                     'permissions' => $permissions
                
             ], 'User found');
         }
         
}
