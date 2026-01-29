<?php

namespace booking\interfaces;

use core\utility\interfaces\Repository;

abstract class BookingRepository extends Repository {
	abstract public function getBooking(int $resourceId, string $date, string $slot);
	abstract public function insertBooking(int $userId, int $resourceId, string $date, string $slot);

}