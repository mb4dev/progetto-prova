<?php

namespace features\booking\strategies;

use core\exceptions\CustomException;
use core\interfaces\BookingStrategy;
use core\interfaces\FieldBookingRepository;
use core\interfaces\FieldsRepository;

class FieldBookingStrategy implements BookingStrategy {

	public function __construct(
		private FieldsRepository $fieldsRepo,
		private FieldBookingRepository $bookingRepo
	) {}

	public function execute(array $params): array {
		throw new CustomException("Metodo execute non supportato, usa insertBooking o getOccupiedSlots", 500);
	}

	public function insertBooking(int $userId, int $fieldId, string $date, string $slot): array {
		$this->fieldsRepo->getResourceById($fieldId);

		if (strtotime($date) < strtotime('today')) {
			throw new CustomException("$date non può essere una data passata", 400);
		}

		$this->validateSlot($slot);

		$bookingId = $this->bookingRepo->insertBooking($userId, $fieldId, $date, $slot);
		return ["booking_id" => $bookingId];
	}

	public function getOccupiedSlots(int $fieldId, string $date): array {
		$this->fieldsRepo->getResourceById($fieldId);

		$occupied = $this->bookingRepo->getOccupiedSlots($fieldId, $date);
		return ["occupied" => $occupied];
	}

	private function validateSlot(string $slot): void {
		$slotParts = explode(":", $slot);
		if (count($slotParts) !== 2) {
			throw new CustomException("formato slot malformato, HH:mm", 400);
		}
		[$hour, $minutes] = $slotParts;
		if ($minutes !== "00" && $minutes !== "30") {
			throw new CustomException("HH:mm, mm deve essere 00 o 30", 400);
		}
	}
}
