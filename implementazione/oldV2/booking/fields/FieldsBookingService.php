<?php 

namespace booking\fields;

use InvalidArgumentException;
use auth\interfaces\AuthRepository;
use booking\interfaces\BookingRepository;
use booking\interfaces\BookingService;
use core\exceptions\ValidationException;
use resources\interfaces\FieldsRepository;

class FieldsBookingService implements BookingService {

	public function __construct(
		private FieldsRepository $fieldsRepo, 
		private BookingRepository $bookingRepo) {}

	public function insertBooking(int $userId, int $resourceId, string $date, string $slot) : array{
		$field = $this->fieldsRepo->getFieldById($resourceId);

		if(strtotime($date) < strtotime('today')){
			throw new ValidationException("$date non può essere una data passata", 400);
		}

		$bookingId = $this->bookingRepo->insertBooking($userId, $resourceId, $date, $slot);

		return ["booking_id" => $bookingId];
	}
}