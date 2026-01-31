<?php 


namespace features\booking\fields;

use core\exceptions\CustomException;
use core\interfaces\BookingRepository;
use core\interfaces\BookingService;
use core\interfaces\FieldsRepository;
use features\resources\ResourceType;

class FieldsBookingService implements BookingService {

	public function __construct(
		private FieldsRepository $fieldsRepo, 
		private BookingRepository $bookingRepo) {}

	public function insertBooking(int $userId, int $resourceId, string $date, string $slot) : array{
		$field = $this->fieldsRepo->getResourceById($resourceId);

		if(strtotime($date) < strtotime('today')){
			throw new CustomException("$date non può essere una data passata", 400);
		}

		$slotParts = explode(":", $slot);
		if(count($slotParts) !== 2) throw new CustomException("formato slot malformato, HH:mm", 400);

		[$hour, $minutes] = $slotParts;

		if($minutes !== "00" && $minutes !== "30") throw new CustomException("HH:mm, mm deve essere 00 o 30 ", 400);
		
		$bookingId = $this->bookingRepo->insertBooking($userId, $resourceId, $date, $slot);
		return ["booking_id" => $bookingId];
	}

	public function getBooking(string $resourceType, int $resourceId, string $date){
		$resource = ResourceType::tryFrom($resourceType);
		if(!$resource) throw new CustomException("risorsa $resourceType sbagliata", 400);

		return ["occupied" => $this->bookingRepo->getBooking($resourceId, $date)];
	}
}