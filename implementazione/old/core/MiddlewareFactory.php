<?php

final class MiddlewareFactory {
    public function __construct() {}

    public function create($type): Middleware {
        return match ($type) {
            'AuthMiddleware' => new AuthMiddleware(),
            default => throw new InvalidArgumentException("Middleware $type non esistente")
        };
    }
}