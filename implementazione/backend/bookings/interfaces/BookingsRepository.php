<?php

abstract class BookingsRepository extends Repository {
	abstract public function getFieldOccupiedSlots(int $fieldId, string $startDay, string $endDay) : array;
	abstract public function getCourseBookingsCount(int $courseId, string $startDay, string $endDay) : array;

	abstract public function getCourseSlots(int $courseId, string $startDay, string $endDay) : array;
	
	//abstract public function addBooking(int $userId, string $tipo, ?int $campoId, ?int $corsoId, string $data, string $slotStart, string $stato, int $quantity = 1) : int;
	//abstract public function updateBookingStatus(int $bookingId, string $stato) : bool;
	
	/*
	abstract public function getBookingsByUserAndStatus(int $userId, string $stato) : array;
	abstract public function checkCapacity(int $courseId, string $data, string $slotStart) : bool;
	abstract public function addPayment(int $userId, float $total, string $tipo) : int;
	abstract public function linkPaymentToBooking(int $paymentId, int $bookingId) : void;
	*/
}