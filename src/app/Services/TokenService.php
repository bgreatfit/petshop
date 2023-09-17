<?php

namespace App\Services;

use App\Contracts\TokenServiceInterface;
use App\Models\JwtToken;
use App\Models\User;
use Carbon\Carbon;
use DateTimeImmutable;
use Illuminate\Support\Facades\Log;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Validator;

class TokenService implements TokenServiceInterface
{
    private Configuration $config;

    public function __construct()
    {
        $privateKeyPath = storage_path('app/private_key.pem');
        $publicKeyPath = storage_path('app/public_key.pem');

        if (!file_exists($privateKeyPath) || !file_exists($publicKeyPath)) {
            throw new \Exception("Could not create Token Service instance");
        }

        $this->config = Configuration::forAsymmetricSigner(
            new Sha256(),
            InMemory::file($privateKeyPath),
            InMemory::file($publicKeyPath)
        );
    }

    public function issueToken(User $user, string $title): string
    {


        $now = new DateTimeImmutable();

        $token = $this->config->builder()
            ->issuedBy(config('app.url'))
            ->permittedFor(config('app.url'))
            ->identifiedBy(bin2hex(random_bytes(16)))
            ->issuedAt($now)
            ->expiresAt($now->modify(config('auth.jwt.expiration')))
            ->withClaim('user_uuid', $user->uuid)
            ->getToken($this->config->signer(), $this->config->signingKey());


        $this->storeToken($user->uuid, $user->id, $title);

        return $token->toString();
    }

    public function parseToken(string $token): array|null
    {
        $parser = new Parser(new JoseEncoder());

        try {
            return $parser->parse($token)->claims()->all();
        } catch (\Throwable $e) {
            $this->logTokenError($e);
        }

        return null;
    }

    public function verifyToken(string $token): bool
    {
        try {
            $parsedToken = $this->config->parser()->parse($token);
            $validator = new Validator();
            $constraints = [
                new SignedWith($this->config->signer(), $this->config->signingKey()),
            ];
            $validator->assert($parsedToken, ...$constraints);

            return true;
        } catch (\Throwable $e) {

            $this->logTokenError($e);
        }

        return false;
    }
    public function isTokenExpired(string $token): bool
    {

        try {
            $parsedToken = $this->config->parser()->parse($token);

            // Get the expiration timestamp from the parsed token
            $expTimestamp = $parsedToken->claims()->get('exp');

            $expTimestampMod = Carbon::instance($expTimestamp);

            // Check if the expiration time is in the past (i.e., token is expired)
            return Carbon::now()->greaterThan($expTimestampMod);

        } catch (\Throwable $e) {
            $this->logTokenError($e);
        }

        return true; // Consider it expired if parsing fails
    }

    private function storeToken(string $uniqueId, int $userId, string $tokenTitle): void
    {
        JwtToken::create([
            'unique_id' => $uniqueId,
            'user_id' => $userId,
            'token_title' => $tokenTitle,
            'created_at' => now(),
            'expires_at' => now()->addSeconds(config('auth.jwt.expiration')),
        ]);
    }

    private function logTokenError($exception): void
    {
        Log::error("TokenValidationFailure: " . $exception->getMessage(), [
            'error_trace' => $exception->getTraceAsString(),
        ]);
    }
}
