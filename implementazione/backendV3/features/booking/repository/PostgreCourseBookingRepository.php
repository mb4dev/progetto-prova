<?php

namespace features\booking\repository;

use core\exceptions\CustomException;
use core\interfaces\CourseBookingRepository;
use features\booking\BookingState;
use features\resources\ResourceType;
use PDO;
use PDOException;

final class PostgreCourseBookingRepository implements CourseBookingRepository {

	public function __construct(private PDO $db) {}

	public function getOccupiedSlots(int $resourceId, string $date): array {
		$query = "
			SELECT 
				slot_start,
				COUNT(*) as booked_count
			FROM centro_sportivo.prenotazioni_corsi
			WHERE corso_id = :resourceId
				AND data = :date
				AND stato IN ('carrello', 'confermata')
			GROUP BY slot_start";

		$stmt = $this->db->prepare($query);
		$stmt->bindValue(":resourceId", $resourceId);
		$stmt->bindValue(":date", $date);
		$stmt->execute();

		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		$occupied = [];
		foreach ($results as $row) {
			$occupied[$row['slot_start']] = (int) $row['booked_count'];
		}
		
		return $occupied;
	}

	public function countBookings(int $resourceId, string $date, string $slot): int {
		$query = "
			SELECT COUNT(*) 
			FROM centro_sportivo.prenotazioni_corsi
			WHERE corso_id = :resourceId
				AND data = :date 
				AND slot_start = :slot
				AND stato IN ('carrello', 'confermata')";

		$stmt = $this->db->prepare($query);
		$stmt->execute([
			':resourceId' => $resourceId,
			':date' => $date,
			':slot' => $slot
		]);

		return (int) $stmt->fetchColumn();
	}

	public function insertBooking(int $userId, int $resourceId, string $date, string $slot): int {
		try {
			$query = '
				INSERT INTO centro_sportivo.prenotazioni_corsi
					(user_id, corso_id, data, slot_start, stato) 
				VALUES (?, ?, ?, ?, ?)
				RETURNING id';
			
			$stmt = $this->db->prepare($query);
			$stmt->execute([
				$userId,
				$resourceId,
				$date,
				$slot,
				BookingState::CART->value
			]);

			return $stmt->fetch(PDO::FETCH_ASSOC)['id'];
		} catch (PDOException $e) {
			if ($e->getCode() === "23505") {
				throw new CustomException("corso $resourceId già prenotato il $date alle $slot", 409);
			}
			throw $e;
		}
	}

}
