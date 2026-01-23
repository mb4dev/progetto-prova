<?php

class DefaultBookingsService implements BookingsService {
	private BookingsRepository $repository;

	public function __construct(BookingsRepository $repo) {
		$this->repository = $repo;
	}

	/*
	public function addFieldToCart(int $userId, int $fieldId, string $data, string $slotStart) : Response {
		try {
			// Check if slot is available
			$occupied = $this->repository->getOccupiedSlots($fieldId, $data, $data);
			if (isset($occupied[$data]) && in_array($slotStart, $occupied[$data])) {
				return new Response(409, false, ["error" => "Slot già occupato"]);
			}

			// Add to cart
			$bookingId = $this->repository->addBooking($userId, 'campo', $fieldId, null, $data, $slotStart, 'carrello');
			return new Response(201, true, ["booking_id" => $bookingId]);
		} catch (Exception $e) {
			return new Response(500, false, ["error" => $e->getMessage()]);
		}
	}
	*/
/*
	public function checkoutFields(int $userId, array $bookingIds) : Response {
		try {
			// Validate bookings belong to user and are cart
			$cartBookings = $this->repository->getBookingsByUserAndStatus($userId, 'carrello');
			$validIds = array_column($cartBookings, 'id');
			$invalidIds = array_diff($bookingIds, $validIds);
			if (!empty($invalidIds)) {
				return new Response(400, false, ["error" => "Prenotazioni non valide"]);
			}

			// Check slots still available
			foreach ($cartBookings as $booking) {
				if (in_array($booking['id'], $bookingIds)) {
					$occupied = $this->repository->getOccupiedSlots($booking['campo_id'], $booking['data'], $booking['data']);
					if (isset($occupied[$booking['data']]) && in_array($booking['slot_start'], $occupied[$booking['data']])) {
						return new Response(409, false, ["error" => "Slot ora occupato"]);
					}
				}
			}

			// Simulate payment (always success for now)
			$total = count($bookingIds) * 50.00; // Example price

			// Confirm bookings
			foreach ($bookingIds as $id) {
				$this->repository->updateBookingStatus($id, 'confermata');
			}

			// Calculate total (example: 50 per field booking)
			$total = count($bookingIds) * 50.00;

			// Confirm bookings
			foreach ($bookingIds as $id) {
				$this->repository->updateBookingStatus($id, 'confermata');
			}

			// Add payment record
			$paymentId = $this->repository->addPayment($userId, $total, 'prenotazione');
			foreach ($bookingIds as $bookingId) {
				$this->repository->linkPaymentToBooking($paymentId, $bookingId);
			}

			return new Response(200, true, ["message" => "Checkout completato", "total" => $total]);
		} catch (Exception $e) {
			return new Response(500, false, ["error" => $e->getMessage()]);
		}
	}

	public function bookCourse(int $userId, int $courseId, string $data, string $slotStart, int $quantity = 1) : Response {
		try {
			// Check capacity
			if (!$this->repository->checkCapacity($courseId, $data, $slotStart)) {
				return new Response(409, false, ["error" => "Capacità esaurita"]);
			}

			// Book directly
			$bookingId = $this->repository->addBooking($userId, 'corso', null, $courseId, $data, $slotStart, 'confermata', $quantity);

			// Add payment
			$total = $quantity * 100.00; // Example price
			$paymentId = $this->repository->addPayment($userId, $total, 'prenotazione');
			$this->repository->linkPaymentToBooking($paymentId, $bookingId);

			return new Response(201, true, ["booking_id" => $bookingId]);
		} catch (Exception $e) {
			return new Response(500, false, ["error" => $e->getMessage()]);
		}
	}
*/
	
	public function getOccupiedlots(string $resourceType, int $resourceId, string $startDay) : Response{
		return match($resourceType) {
			ResourceType::CAMPO->value => $this->getOccupiedFieldSlots($resourceId, $startDay),
			ResourceType::CORSO->value => $this->getOccupiedCourseSlots($resourceId, $startDay),
			default => new Response(400, false, ["error" => "..."])
		};
	}

	private function getWeekLastDay(string $startDate): string{
		$date = new DateTime($startDate);
		$date->add(new DateInterval("P7D"));
		return $date->format("Y-m-d");
	}

	private function getOccupiedFieldSlots(string $resourceId, string $startDay) : Response{
		try {
			$endDay = $this->getWeekLastDay($startDay);
			$result = $this->repository->getFieldOccupiedSlots($resourceId, $startDay, $endDay);
			return new Response(200, true, $result);
		}
		catch(Exception $e){
			return new Response(500, false, ["error" => $e->getMessage()]);
		}
	}

	private function getOccupiedCourseSlots(string $resourceId, string $startDay) : Response{
		try {
			$endDay = $this->getWeekLastDay($startDay);
			$result = $this->repository->getCourseOccupiedSlots($resourceId, $startDay, $endDay);
			return new Response(200, true, $result);
		}
		catch(Exception $e){
			return new Response(500, false, ["error" => $e->getMessage()]);
		}
	}

}