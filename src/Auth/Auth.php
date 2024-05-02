<?php

namespace MiniRestFramework\Auth;

use DomainException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use MiniRestFramework\Exceptions\AccessNotAllowedException;
use MiniRestFramework\Exceptions\InvalidJWTToken;
use MiniRestFramework\Exceptions\UserNotFoundException;
use MiniRestFramework\Http\Request\Request;
use UnexpectedValueException;

class Auth
{
    public static string $secretKey;
    public static int $tokenExpiration; // Tempo de expiração em segundos (1 hora)

    public function __construct()
    {
    }

    /**
     * @throws UserNotFoundException
     * @throws AccessNotAllowedException
     */
    public static function attempt(string $email, string $password, $user): bool|string
    {
        $user = $user->where('email', $email)->first();

        if (!$user) {
            throw new UserNotFoundException();
        }

        if (!password_verify($password, $user->password)) {
            throw new UserNotFoundException();
        }

        return self::createToken($user);
    }

    /**
     * @throws InvalidJWTToken
     */
    public static function check(Request $request): bool
    {
        $token = self::getTokenFromRequest($request);
        return self::validateToken($token);
    }

    /**
     * @throws InvalidJWTToken
     */
    public static function user(Request $request): ?object
    {
        $token = self::getTokenFromRequest($request);

        return self::validateToken($token) ? JWT::decode($token, new Key(self::$secretKey, 'HS256')) : null;
    }

    public static function id(Request $request): ?int
    {
        $token = self::user($request);
        return $token?->id;
    }

    private static function getTokenFromRequest(Request $request): ?string
    {
        $authorizationHeader = $request->headers('Authorization');

        return preg_match('/Bearer\s+(.*)/', $authorizationHeader, $matches) ? $matches[1] : null;
    }

    /**
     * @throws InvalidJWTToken
     */
    public static function validateToken($token): bool
    {
        if ($token === null) throw new InvalidJWTToken('Token NULL, Token inválido.');

        try {
            JWT::decode($token, new Key(self::$secretKey, 'HS256'));
            return true;
        } catch (ExpiredException|DomainException|UnexpectedValueException) {
            throw new InvalidJWTToken();
        }

    }

    /**
     * @param string $secretKey
     */
    public static function setSecretKey(string $secretKey): void
    {
        self::$secretKey = $secretKey;
    }

    /**
     * @param int $tokenExpiration
     */
    public static function setTokenExpiration(int $tokenExpiration): void
    {
        self::$tokenExpiration = $tokenExpiration;
    }

    public static function createToken(int $id): ?string
    {
        $now = time();
        $expiration = $now + Auth::$tokenExpiration;

        $payload = [
            'id' => $id,
            'iat' => $now,       // Timestamp de emissão do token
            'exp' => $expiration // Timestamp de expiração do token
        ];

        return JWT::encode($payload, Auth::$secretKey, 'HS256');
    }
}