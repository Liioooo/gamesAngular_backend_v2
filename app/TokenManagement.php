<?php

namespace app;
use Firebase\JWT\JWT;
use Exception;

class TokenManagement {
    static function generateToken($userID) {
        $expireTime = time() + (60 * 60);
        $keyPayload = [
            'iss' => 'lio-games.ddns.net',
            'iat' => time(),
            'exp' => $expireTime,
            'userID' => $userID
        ];
        $token = JWT::encode($keyPayload, Constants::SECRET_KEY);
        setcookie('jwt-token', $token, $expireTime, null, null, null, true);
    }

    static function verifyToken(API $api): string {
        try {
            $payload = JWT::decode($_COOKIE['jwt-token'], Constants::SECRET_KEY, ['HS256']);
            return $payload->userID;
        } catch (Exception $e) {
            $api->throwError(Constants::INVALID_TOKEN, 'Token is invalid');
            return null;
        }
    }
}