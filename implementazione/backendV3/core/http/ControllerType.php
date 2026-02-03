<?php

namespace core\http;

use core\exceptions\CustomException;
use features\auth\controller\AuthController;
use features\booking\controller\BookingController;
use features\resources\controller\ResourceController;

enum ControllerType: string {
	case AUTH = "auth";
	case AUTHV2 = "authv2";
	case RESOURCES = "resources";
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

