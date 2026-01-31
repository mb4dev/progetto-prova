<?php

namespace core\http;

use auth\AuthController;
use booking\BookingController;
use resources\ResourceController;

enum ControllerTypes: string {
	case AUTH = AuthController::class;
	case RESOURCE = ResourceController::class;
	case BOOKING = BookingController::class;
}
