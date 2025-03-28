<?php

namespace App\Services;

use App\DTOs\LoginDTO;
use App\Repositories\Contracts\AuthRepositoryInterface;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class AuthService
{

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
}
