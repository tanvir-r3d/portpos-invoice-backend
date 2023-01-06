<?php

namespace App\Services\JWTService;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use OpenSSLAsymmetricKey;

final class JWTService
{
    public const ALGO = 'RS256';

    private OpenSSLAsymmetricKey $privateKey;

    private OpenSSLAsymmetricKey $publicKey;

    private array $payload;

    private function __construct()
    {
        $this->privateKey = $this->setPrivateKey();
        $this->publicKey = $this->setPublicKey();
    }

    public static function make(): JWTService
    {
        return new static();
    }

    public function setPayload($payload, $host): JWTService
    {
        $this->payload = [
            'serverDomain' => $host,
            'expiresAt' => $this->expiresIn(),
            'uuid' => $payload,
        ];
        return $this;
    }

    public function getPrivateKey(): OpenSSLAsymmetricKey
    {
        return $this->privateKey;
    }

    public function setPrivateKey(): OpenSSLAsymmetricKey
    {
        return openssl_get_privatekey(file_get_contents($_ENV['JWT_PRIVATE_KEY_PATH']), $_ENV['JWT_PASS_PHRASE']);
    }

    public function getPublicKey(): OpenSSLAsymmetricKey
    {
        return $this->publicKey;
    }

    public function setPublicKey(): OpenSSLAsymmetricKey
    {
        return openssl_get_publickey(file_get_contents($_ENV['JWT_PUBLIC_KEY_PATH']));
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function createToken(): string
    {
        return JWT::encode($this->getPayload(), $this->getPrivateKey(), self::ALGO);
    }

    public function decodeToken($token): array
    {
        $decoded = JWT::decode($token, new Key($this->getPublicKey(), self::ALGO));
        return (array)$decoded;
    }

    public function expiresIn(): float|int
    {
        return time() + 60 * 60 * 24 * $_ENV['JWT_EXPIRES_IN_DAY'];
    }
}
