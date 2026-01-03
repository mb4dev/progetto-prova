<?php

class AuthMiddleware implements Middleware {
    public function __construct(private JwtTokenManager $jwtTokenManager) {}

    public function handle(): ?Response {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

        if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return new Response(401, false, ["error" => "Token mancante o non valido"]);
        }

        $token = $matches[1];

        try {
            $payload = $this->jwtTokenManager->decode($token);
            if (!$payload) {
                 return new Response(401, false, ["error" => "Token non valido"]);
            }
        } catch (Exception $e) {
            return new Response(401, false, ["error" => "Token non valido: " . $e->getMessage()]);
        }

        return null;
    }
}
