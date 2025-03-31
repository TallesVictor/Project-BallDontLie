<?php
namespace App\Contracts;

use App\DTOs\LoginDTO;

interface AuthServiceContract
{
    public function login(LoginDTO $credentials);
    public function logout();
    public function getUser();
}
