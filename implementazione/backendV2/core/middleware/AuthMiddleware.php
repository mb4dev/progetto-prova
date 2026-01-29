<?php

namespace core\middleware;

use auth\interfaces\AuthRepository;
use core\exceptions\AuthException;
use core\middleware\interfaces\Middleware;
use core\utility\interfaces\JwtTokenService;

/**
 * Middleware per l'autenticazione
 * 
 * Questo middleware:
 * 1. Estrae il token JWT dall'header Authorization
 * 2. Verifica e decodifica il token
 * 3. Carica l'utente dal database
 * 4. Aggiunge l'utente al contesto
 */
class AuthMiddleware implements Middleware {
    
    public function __construct(
        private AuthRepository $authRepo,
        private JwtTokenService $tokenService
    ) {}

    /**
     * Gestisce l'autenticazione
     * 
     * @param array $context Contesto della request
     * @return array Contesto con l'utente aggiunto
     * @throws AuthException Se l'autenticazione fallisce
     */
    public function handle(array $context): array {
        $token = $this->getToken();
        
        if (!$token) {
            throw new AuthException("Token mancante", 401);
        }

        // Decodifica il token JWT
        $payload = $this->tokenService->decode($token);
        
        // Carica l'utente dal database
        $user = $this->authRepo->getUserById($payload->id);
        
        // Aggiunge l'utente al contesto
        $context['user'] = $user;
        
        return $context;
    }

    /**
     * Estrae il token JWT dall'header Authorization
     * 
     * @return string|null Il token o null se non presente
     */
    private function getToken(): ?string {
        $header = trim($_SERVER["HTTP_AUTHORIZATION"] ?? "");
        
        if ($header === "") {
            return null;
        }

        $headerParts = explode(" ", $header);
        
        if (count($headerParts) !== 2) {
            return null;
        }
        
        [$scheme, $token] = $headerParts;

        if (strtolower($scheme) !== "bearer") {
            return null;
        }

        return $token;
    }
}
