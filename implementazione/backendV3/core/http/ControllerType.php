<?php

namespace core\http;

use core\exceptions\CustomException;
use features\auth\controller\AuthController;
use features\booking\controller\BookingController;
use features\payments\controller\PaymentController;
use features\resources\controller\ResourceController;

enum ControllerType: string {
	case AUTH = "auth";
    case RESOURCE = 'resource';
    case BOOKING = 'booking';
    case PAYMENT = 'payment';

    public function getClass(): string {
        return match($this) {
            self::AUTH => AuthController::class,
            self::RESOURCE => ResourceController::class,
            self::BOOKING => BookingController::class,
            self::PAYMENT => PaymentController::class,
			default => throw new CustomException("controller sconosciuto", 400)
        };
    }
}

