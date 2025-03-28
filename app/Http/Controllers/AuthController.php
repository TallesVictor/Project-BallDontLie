<?php

namespace App\Http\Controllers;

use App\DTOs\LoginDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\AuthTokenResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function __construct(private AuthService $authService) {}

    public function login(LoginRequest $request)
    {
        $dto = LoginDTO::fromArray($request->validated());
        $user =  $this->authService->login($dto);
        
        if (!$user) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        return response()->json(
            UserResource::make($user)
        )
            ->header('X-Authorization', "Bearer $user->token")
            ->setStatusCode(200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout successfully'], 200);
    }
}
