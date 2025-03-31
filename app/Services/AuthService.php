<?php

namespace App\Services;

use App\Contracts\AuthServiceContract;
use App\DTOs\LoginDTO;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;

class AuthService implements AuthServiceContract
{

    protected $auth;
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
        
    }
    
    public function login(LoginDTO $credentials)
    {
        $userRepository = app(UserRepository::class);
        $user = $userRepository->findByEmail($credentials->email);

        if ($user && Auth::attempt($credentials->toArray())) {

            $userAuth = Auth::user();
            
            $token = $user->createToken('API Token')->plainTextToken;
            $userAuth->token = $token;
            
            return $userAuth;

        }

        return null;
    }

    public function getUser()
    {
        return $this->auth->user();
    }

    public function logout(){
        $this->auth->user()->currentAccessToken()->delete();
    }
}
