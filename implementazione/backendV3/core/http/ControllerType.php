<?php

namespace core\http;

use core\exceptions\CustomException;
use features\auth\AuthController;
use features\booking\BookingController;
use features\resources\ResourceController;

enum ControllerType: string {
	case AUTH = "auth";
    case RESOURCE = 'resource';
    case BOOKING = 'booking';

    public function getClass(): string {
        return match($this) {
            self::AUTH => AuthController::class,
            self::RESOURCE => ResourceController::class,
            self::BOOKING => BookingController::class,
			default => throw new CustomException("controller sconosciuto", 400)
        };
    }
}

// Nel Router:
