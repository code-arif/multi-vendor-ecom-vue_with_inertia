<?php
namespace App\Helper;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTToken
{
    //token generate
    public static function generateToken($email, $id)
    {
        $key = config('app.jwt_key');
        if (!is_string($key) || empty($key)) {
            throw new Exception('JWT_KEY is not properly set or is not a string.');
        } else {
            $payload = [
                'iss' => 'laravel_jwt_token',
                'iat' => time(),
                'exp' => time() + 60 * 60 * 24 * 7,
                'email' => $email,
                'id' => $id,
            ];
            $token = JWT::encode($payload, $key, 'HS256');
            return $token;
        }
    }

    //token read
    public static function verifyToken($token)
    {
        try {
            if ($token == null) {
                return 'unauthorized';
            } else {
                $key = config('app.jwt_key');
                if (!is_string($key) || empty($key)) {
                    throw new Exception('JWT_KEY is not properly set or is not a string.');
                } else {
                    $decoded = JWT::decode($token, new Key($key, 'HS256'));
                    return $decoded;
                }
            }
        }catch (Exception $e){
            // return $e->getMessage();
            return 'unauthorized';
        }
    }
}