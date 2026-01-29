<?php

namespace core\middleware\interfaces;

/**
 * Interfaccia per i Middleware
 * 
 * Un middleware può processare una request prima che venga eseguito il command.
 * Utile per autenticazione, validazione, logging, etc.
 * 
 * Esempio:
 * class AuthMiddleware implements Middleware {
 *     public function handle(array $context): array {
 *         // Verifica token, carica utente
 *         $context['user'] = $user;
 *         return $context;
 *     }
 * }
 */
interface Middleware {
    /**
     * Processa il contesto della request
     * 
     * @param array $context Contesto della request (body, query params, etc.)
     * @return array Contesto modificato
     * @throws Exception Se il middleware fallisce (es. auth non valida)
     */
    public function handle(array $context): array;
}
