<?php

namespace features\booking\repository;

use core\interfaces\BookingHistoryRepository;
use PDO;

final class PostgreBookingHistoryRepository implements BookingHistoryRepository {

	public function __construct(private PDO $db) {}

	public function getHistoryForUser(int $userId): array {
		$query = "
			SELECT 
				pr.id,
				'campo' AS tipo,
				c.sport AS title,
				pr.data,
				pr.slot_start AS slot,
				c.price AS amount,
				pr.stato
			FROM centro_sportivo.prenotazioni_campi pr
			JOIN centro_sportivo.campi c ON pr.campo_id = c.id
			WHERE pr.user_id = :userId

			UNION ALL

			SELECT 
				pr.id,
				'corso' AS tipo,
				co.name AS title,
				pr.data,
				pr.slot_start AS slot,
				co.price AS amount,
				pr.stato
			FROM centro_sportivo.prenotazioni_corsi pr
			JOIN centro_sportivo.corsi co ON pr.corso_id = co.id
			WHERE pr.user_id = :userId

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
		$stmt->bindValue(":userId", $userId);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
}
