<?php

namespace features\booking\repository;

use core\exceptions\CustomException;
use core\interfaces\FieldBookingRepository;
use features\booking\BookingState;
use features\resources\ResourceType;
use PDO;
use PDOException;

final class PostgreFieldBookingRepository implements FieldBookingRepository {

	public function __construct(private PDO $db) {}

	public function getOccupiedSlots(int $resourceId, string $date): array {
		$query = "
			SELECT slot_start 
			FROM centro_sportivo.prenotazioni_campi
			WHERE campo_id = :resourceId
				AND data = :date
				AND stato IN ('carrello', 'confermata')";

		$stmt = $this->db->prepare($query);
		$stmt->bindValue(":resourceId", $resourceId);
		$stmt->bindValue(":date", $date);
		$stmt->execute();


		return $stmt->fetchAll(PDO::FETCH_COLUMN);
	}

	public function insertBooking(int $userId, int $resourceId, string $date, string $slot): int {
		try {
			$query = '
				INSERT INTO centro_sportivo.prenotazioni_campi
					(user_id, campo_id, data, slot_start, stato) 
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
				throw new CustomException("campo $resourceId già prenotato il $date alle $slot", 409);
			}
			throw $e;
		}
	}

	public function getBooking(int $id) : array{
		$query = "
			SELECT *
			FROM centro_sportivo.prenotazioni_campi pc, centro_sportivo.campi c
			WHERE pc.id = :id AND pc.campo_id = c.id";

		$stmt = $this->db->prepare($query);
		$stmt->bindParam(":id", $id);

		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
}
