<?php

namespace features\booking\strategies;

use core\exceptions\CustomException;
use core\interfaces\BookingStrategy;
use core\interfaces\CourseBookingRepository;
use core\interfaces\CoursesRepository;

class CourseBookingStrategy implements BookingStrategy {
	
	public function __construct(
		private CoursesRepository $coursesRepo,
		private CourseBookingRepository $bookingRepo
	) {}

	public function execute(array $params): array {
		throw new CustomException("Metodo execute non supportato, usa insertBooking o getOccupiedSlots", 500);
	}

	public function insertBooking(int $userId, int $courseId, string $date, string $slot): array {
	
		$course = $this->coursesRepo->getResourceById($courseId);

		if (strtotime($date) < strtotime('today')) {
			throw new CustomException("$date non può essere una data passata", 400);
		}

		$this->validateCourseSlot($course, $slot);
		$this->checkCapacity($courseId, $date, $slot, $course['capacity']);
		
		$bookingId = $this->bookingRepo->insertBooking($userId, $courseId, $date, $slot);
		return ["booking_id" => $bookingId];
	
	}

	public function getOccupiedSlots(int $courseId, string $date): array {
	
		$course = $this->coursesRepo->getResourceById($courseId);
		$booked = $this->bookingRepo->getOccupiedSlots($courseId, $date);

		$available = [];
		foreach ($course['schedule'] as $slot) {
			$bookedCount = $booked[$slot] ?? 0;
			$available[$slot] = [
				"booked" => $bookedCount,
				"available" => max(0, $course['capacity'] - $bookedCount),
				"capacity" => $course['capacity'] ,
				"is_full" => $course['capacity'] === $bookedCount
			];
		}

		return [$available];
		
	}

	private function validateCourseSlot(array $course, string $slot): void {
		if (!in_array($slot, $course['schedule'] ?? [])) {
			throw new CustomException("Slot {$slot} non valido per questo corso", 400);
		}
	}

	private function checkCapacity(int $courseId, string $date, string $slot, int $capacity): void {
		$existing = $this->bookingRepo->countBookings($courseId, $date, $slot);
		if ($existing >= $capacity) {
			throw new CustomException("Corso al completo per il {$date} alle {$slot}", 409);
		}
	}
		
}
