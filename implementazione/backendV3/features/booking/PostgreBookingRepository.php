<?php

namespace features\booking;

use core\exceptions\CustomException;
use core\interfaces\BookingRepository;
use features\resources\ResourceType;
use PDO;
use PDOException;

final class PostgreBookingRepository implements BookingRepository {

	public function __construct(private PDO $db) {}

	public function getBooking(int $resourceId, string $date) {
		$query = "
			SELECT slot_start 
			FROM centro_sportivo.prenotazioni 
			WHERE tipo = 'campo' 
				AND campo_id = :resourceId
				AND data = :date
				AND stato IN ('carrello', 'confermata')";

		$stmt = $this->db->prepare($query);
		$stmt->bindParam(":resourceId", $resourceId);
		$stmt->bindParam(":date", $date);

		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_COLUMN);

		return $result;
	}

	public function insertBooking(int $userId, int $resourceId, string $date, string $slot) {
		try {
			$query = '
				INSERT INTO centro_sportivo.prenotazioni (user_id, tipo, campo_id, corso_id, data, slot_start, stato, quantity) 
				VALUES (?, ?, ?, ?, ?, ?, ?, ?)
				RETURNING id';
			$stmt = $this->db->prepare($query);
			$stmt->execute([$userId, ResourceType::FIELD->value, $resourceId, null, $date, $slot, BookingState::CART->value, 1]);

			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			return $result['id'];
		}
		catch(PDOException $e) {
			if($e->getCode() === "23505") {
				throw new CustomException("campo $resourceId già prenotato il $date alle $slot", 409);
			}
		}
	}

	public function getBookingsForUser(int $userId): array {
		$query = "
			SELECT 
				pr.id,
				'campo' AS tipo,
				c.sport AS title,
				pr.data,
				pr.slot_start AS slot,
				c.price AS amount,
				pr.stato
			FROM centro_sportivo.prenotazioni pr
			JOIN centro_sportivo.campi c ON pr.campo_id = c.id
			WHERE pr.user_id = :userId AND pr.tipo = 'campo'

			UNION ALL

			SELECT 
				pr.id,
				'corso' AS tipo,
				co.name AS title,
				pr.data,
				pr.slot_start AS slot,
				co.price AS amount,
				pr.stato
			FROM centro_sportivo.prenotazioni pr
			JOIN centro_sportivo.corsi co ON pr.corso_id = co.id
			WHERE pr.user_id = :userId AND pr.tipo = 'corso'

			UNION ALL

			SELECT 
				au.id,
				'abbonamento' AS tipo,
				a.nome AS title,
				au.data_inizio AS data,
				NULL::time AS slot,
				a.prezzo AS amount,
				CASE WHEN au.attivo THEN 'attivo' ELSE 'scaduto' END AS stato
			FROM centro_sportivo.abbonamenti_utenti au
			JOIN centro_sportivo.abbonamenti a ON au.abbonamento_id = a.id
			WHERE au.user_id = :userId

			ORDER BY data DESC
		";

		$stmt = $this->db->prepare($query);
		$stmt->bindParam(":userId", $userId);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $result;
	}
}
