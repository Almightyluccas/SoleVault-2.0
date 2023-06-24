<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\signupRequest;
use app\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller {
    public function login(LoginRequest $request) {
      $credentials = $request->validated() ;
      if(Auth::attempt($credentials)) {
        return response([
          'message' => 'Provide email address or password is incorrect'
        ]) ;
      }
      /** @var User $user */
      $user = Auth::user() ;
      $token = $user->createToken('main')->plainTextToken ;
      return response(compact('user', 'token')) ;
    }

    public function signup(signupRequest $request) {
      $data = $request->validated() ;
      /** @var User $user */
      $user = User::create([
        'name' => $data['name'] ,
        'email' => $data['email'] ,
        'password' => bcrypt($data['password'])
      ]) ;

      $token = $user->createToken('main')->plainTextToken ;

      return resposne(compact('user', 'token')) ;
    }

    public function logout(Request $request) {
      /** @var User $user */
      $user = $request->user() ;
      $user->currentAccessToken()->delete() ;
      return response('', 204) ;

    }
}
