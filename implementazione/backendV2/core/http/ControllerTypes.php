<?php

namespace core\http;
enum ControllerTypes: string {
	case AUTH = "auth";
	case RESOURCE = "resource";
	case BOOKINGS = "bookings";
}
