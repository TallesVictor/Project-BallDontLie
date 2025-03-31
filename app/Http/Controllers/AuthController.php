<?php

namespace App\Http\Controllers;

use App\Contracts\AuthServiceContract;
use App\DTOs\LoginDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function login(LoginRequest $request)
    {
        $authService = app(AuthServiceContract::class);

        $dto = LoginDTO::fromArray($request->validated());
        $user =  $authService->login($dto);

        if (!$user) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        return response()->json(
            UserResource::make($user)
        )
            ->header('X-Authorization', "Bearer $user->token")
            ->setStatusCode(200);
    }

    public function logout()
    {
        $authService = app(AuthServiceContract::class);
        
        $authService->logout();
        return response()->json(['message' => 'Logout successfully'], 200);
    }
}
