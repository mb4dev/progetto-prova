<?php

namespace core\http;

use features\auth\AuthController;

enum ControllerType: string {
	case AUTH = "auth";
    case RESOURCE = 'resource';

    public function getClass(): string {
        return match($this) {
            self::AUTH => AuthController::class
        };
    }
}

// Nel Router:
