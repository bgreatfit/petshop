<?php
// app/Contracts/TokenService.php

namespace App\Contracts;

use App\Models\User;

interface TokenServiceInterface
{
    public function issueToken(User $user, string $title);
    public function parseToken(string $token);
    public function verifyToken(string $token);
}
