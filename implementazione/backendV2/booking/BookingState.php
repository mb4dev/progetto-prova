<?php

namespace booking;
enum BookingState : string {
	case CART = "carrello";
	case CONFIRMED = "confermata";
	case CANCELLED = "cancellata";
}