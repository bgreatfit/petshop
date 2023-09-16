<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Rsa\Sha256;

class GenerateJWTKeys extends Command
{
    protected $signature = 'jwt:generate-keys';
    protected $description = 'Generate JWT private and public keys';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {
            // Generate private key (PEM format)
            $privateKey = openssl_pkey_new([
                'private_key_bits' => 2048,
                'private_key_type' => OPENSSL_KEYTYPE_RSA,
            ]);

            if ($privateKey === false) {
                throw new \RuntimeException('Failed to generate private key.');
            }

            openssl_pkey_export($privateKey, $privateKeyPEM);

            if ($privateKeyPEM === false) {
                throw new \RuntimeException('Failed to export private key.');
            }

            // Generate public key (PEM format)
            $publicKey = openssl_pkey_get_details($privateKey);

            if ($publicKey === false) {
                throw new \RuntimeException('Failed to get public key details.');
            }

            $publicKeyPEM = $publicKey['key'];

            // Store the keys in the storage/app directory
            Storage::put('app/private_key.pem', $privateKeyPEM);
            Storage::put('app/public_key.pem', $publicKeyPEM);

            $this->info('JWT keys generated and stored in storage/app directory.');
        } catch (\Exception $e) {
            $this->error('An error occurred while generating JWT keys:');
            $this->error($e->getMessage());
        }
    }
}
