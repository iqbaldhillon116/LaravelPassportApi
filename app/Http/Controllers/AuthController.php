<?php
       namespace App\Http\Controllers;
       
       use Illuminate\Http\Request;
       
       use Illuminate\Support\Facades\Auth;
       
       use App\User;
       
       class AuthController extends Controller {
       
           public function register(Request $request){
       
               $request->validate([
       
                   'name' => 'required|string',
       
                   'email' => 'required|string|email|unique:users',
       
                   'password' => 'required|string|confirmed'
       
               ]);
       
               $user = new User([
       
                   'name' => $request->name,
       
                   'email' => $request->email,
       
                   'password' => bcrypt($request->password)
       
               ]);
       
               $user->save();
       
               return response()->json([
       
                   'message' => 'Successfully created user!'
       
               ], 201);
       
           }
       
           public function login(Request $request){
       
               $request->validate([
       
                   'email' => 'required|string|email',
       
                   'password' => 'required|string'
       
               ]);
       
               $credentials = request(['email', 'password']);
       
               if(!Auth::attempt($credentials))
       
                   return response()->json([
       
                       'message' => 'Unauthorized'
       
                   ], 401);
       
               $user = $request->user();
       
               $tokenResult = $user->createToken('token');
       
               $token = $tokenResult->token;
       
               $token->save();
       
               return response()->json([
       
                   'access_token' => $tokenResult->accessToken,
       
                   'token_type' => 'Bearer'
       
               ]);
       
           }
       
           public function get_user(Request $request){
       
               return response()->json($request->user());
       
           }
       
       }
