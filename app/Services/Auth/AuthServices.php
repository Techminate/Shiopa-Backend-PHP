<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Hash;

//Interface
use App\Contracts\User\UserRepositoryInterface;


class AuthServices{

    private $repositoryInterface;

    public function __construct(UserRepositoryInterface $repositoryInterface){
        $this->ri = $repositoryInterface;
    }

    public function registerCustomer($request){
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = $this->ri->userCreate([
            'role_id' => '0',
            'is_admin' => 0,
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function registerAdmin($request){
        if(auth()->user()->is_admin == true){
            $fields = $request->validate([
                'role_id'=>'required',
                'name' => 'required|string',
                'email' => 'required|string|unique:users,email',
                'password' => 'required|string|confirmed'
            ]);
    
            $user = $this->ri->userCreate([
                'role_id' => $fields['role_id'],
                'is_admin' => 1,
                'name' => $fields['name'],
                'email' => $fields['email'],
                'password' => bcrypt($fields['password'])
            ]);
    
            $token = $user->createToken('myapptoken')->plainTextToken;
    
            $response = [
                'user' => $user,
                'token' => $token
            ];
    
            return response($response, 201);
        }else{
            $response = [
                'user' => 'You are not Authorized',
            ];
    
            return response($response, 401);
        }
    }

    public function loginCustomer($request) {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // Check email
        $user = $this->ri->userGetByEmail($fields['email']);

        // Check password
        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Email or Password Did Not Match!'
            ], 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return response($response, 200);
    }

    public function loginAdmin($request) {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // Check email
        $user = $this->ri->userGetByEmail($fields['email']);
        if($user->is_admin == true){
            if(!$user || !Hash::check($fields['password'], $user->password)) {
                return response([
                    'message' => 'Email or Password Did Not Match!'
                ], 401);
            }
    
            $token = $user->createToken('myapptoken')->plainTextToken;
    
            $response = [
                'user' => $user,
                'token' => $token,
            ];
    
            return response($response, 200);
        }else{
            $response = [
                'user' => 'You are not Authorized',
            ];
    
            return response($response, 401);
        }
    }

    public function logout(){
        $this->ri->userAuthenticated()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }
}