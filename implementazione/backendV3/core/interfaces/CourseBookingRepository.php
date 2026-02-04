<?php

namespace core\interfaces;

interface CourseBookingRepository extends BookingRepository {
	public function countBookings(int $resourceId, string $date, string $slot): int;
}
