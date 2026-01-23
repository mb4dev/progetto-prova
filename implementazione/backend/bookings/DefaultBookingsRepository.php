<?php

final class DefaultBookingsRepository extends BookingsRepository {

	public function __construct(PDO $connection) {
		parent::__construct($connection);
	}

	public function getFieldOccupiedSlots(int $fieldId, string $startDay, string $endDay) : array {
		$query = "
			SELECT data, slot_start
			FROM centro_sportivo.prenotazioni
			WHERE tipo = 'campo' AND campo_id = :fieldId AND data >= :startDay AND data <= :endDay
			AND stato IN ('carrello', 'confermata')
			ORDER BY data, slot_start";

		$stmt = $this->db->prepare($query);
		$stmt->bindParam(':fieldId', $fieldId, PDO::PARAM_INT);
		$stmt->bindParam(':startDay', $startDay, PDO::PARAM_STR);
		$stmt->bindParam(':endDay', $endDay, PDO::PARAM_STR);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$result = [];
		foreach ($rows as $row) {
			$result[$row['data']][] = $row['slot_start'];
		}
		return $result;
	}

	public function getCourseOccupiedSlots(int $courseId, string $startDay, string $endDay): array{
		return [];
	}
	/*
	public function addBooking(int $userId, string $tipo, ?int $campoId, ?int $corsoId, string $data, string $slotStart, string $stato, int $quantity = 1) : int {
		$query = "
			INSERT INTO centro_sportivo.prenotazioni (user_id, tipo, campo_id, corso_id, data, slot_start, stato, quantity)
			VALUES (:userId, :tipo, :campoId, :corsoId, :data, :slotStart, :stato, :quantity)";

		$stmt = $this->db->prepare($query);
		$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
		$stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
		$stmt->bindParam(':campoId', $campoId, PDO::PARAM_INT);
		$stmt->bindParam(':corsoId', $corsoId, PDO::PARAM_INT);
		$stmt->bindParam(':data', $data, PDO::PARAM_STR);
		$stmt->bindParam(':slotStart', $slotStart, PDO::PARAM_STR);
		$stmt->bindParam(':stato', $stato, PDO::PARAM_STR);
		$stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
		$stmt->execute();
		return $this->db->lastInsertId();
	}

	public function updateBookingStatus(int $bookingId, string $stato) : bool {
		$query = "UPDATE centro_sportivo.prenotazioni SET stato = :stato WHERE id = :id";
		$stmt = $this->db->prepare($query);
		$stmt->bindParam(':stato', $stato, PDO::PARAM_STR);
		$stmt->bindParam(':id', $bookingId, PDO::PARAM_INT);
		return $stmt->execute();
	}

	public function getBookingsByUserAndStatus(int $userId, string $stato) : array {
		$query = "SELECT * FROM centro_sportivo.prenotazioni WHERE user_id = :userId AND stato = :stato";
		$stmt = $this->db->prepare($query);
		$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
		$stmt->bindParam(':stato', $stato, PDO::PARAM_STR);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function checkCapacity(int $courseId, string $data, string $slotStart) : bool {
		// Get course capacity
		$query = "SELECT capacity FROM centro_sportivo.corsi WHERE id = :courseId";
		$stmt = $this->db->prepare($query);
		$stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
		$stmt->execute();
		$course = $stmt->fetch(PDO::FETCH_ASSOC);
		if (!$course) return false;

		$capacity = $course['capacity'];

		// Count confirmed bookings for this course/date/slot
		$query = "
			SELECT SUM(quantity) as occupied
			FROM centro_sportivo.prenotazioni
			WHERE tipo = 'corso' AND corso_id = :courseId AND data = :data AND slot_start = :slotStart AND stato = 'confermata'";

		$stmt = $this->db->prepare($query);
		$stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
		$stmt->bindParam(':data', $data, PDO::PARAM_STR);
		$stmt->bindParam(':slotStart', $slotStart, PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);

		$occupied = $result['occupied'] ?? 0;
		return $occupied < $capacity;
	}

	public function addPayment(int $userId, float $total, string $tipo) : int {
		$query = "INSERT INTO centro_sportivo.pagamenti (user_id, totale, tipo) VALUES (:userId, :total, :tipo)";
		$stmt = $this->db->prepare($query);
		$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
		$stmt->bindParam(':total', $total, PDO::PARAM_STR);
		$stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
		$stmt->execute();
		return $this->db->lastInsertId();
	}

	public function linkPaymentToBooking(int $paymentId, int $bookingId) : void {
		$query = "INSERT INTO centro_sportivo.pagamenti_prenotazioni (pagamento_id, prenotazione_id) VALUES (:paymentId, :bookingId)";
		$stmt = $this->db->prepare($query);
		$stmt->bindParam(':paymentId', $paymentId, PDO::PARAM_INT);
		$stmt->bindParam(':bookingId', $bookingId, PDO::PARAM_INT);
		$stmt->execute();
	}
		*/
}