<?php 


namespace features\booking\fields;

use core\exceptions\CustomException;
use core\interfaces\BookingRepository;
use core\interfaces\BookingService;
use core\interfaces\FieldsRepository;

class FieldsBookingService implements BookingService {

	public function __construct(
		private FieldsRepository $fieldsRepo, 
		private BookingRepository $bookingRepo) {}

	public function insertBooking(int $userId, int $resourceId, string $date, string $slot) : array{
		$field = $this->fieldsRepo->getResourceById($resourceId);

		if(strtotime($date) < strtotime('today')){
			throw new CustomException("$date non può essere una data passata", 400);
		}

		$bookingId = $this->bookingRepo->insertBooking($userId, $resourceId, $date, $slot);

		return ["booking_id" => $bookingId];
	}
}