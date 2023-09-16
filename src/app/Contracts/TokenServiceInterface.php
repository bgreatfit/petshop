<?php
// app/Contracts/TokenService.php

namespace App\Contracts;

use App\Models\User;

interface TokenServiceInterface
{
    public function issueToken(User $user, string $title):string;
    public function parseToken(string $token): mixed;
    public function verifyToken(string $token): bool;
}
