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

	public function getCourseBookingsCount(int $courseId, string $startDay, string $endDay): array {
		$query = "
			SELECT p.data, p.slot_start, CAST(COUNT(*) as int) as count, c.capacity
			FROM centro_sportivo.prenotazioni p
			JOIN centro_sportivo.corsi c ON p.corso_id = c.id
			WHERE p.tipo = 'corso' 
			AND p.corso_id = :courseId 
			AND p.data >= :startDay 
			AND p.data <= :endDay
			AND p.stato IN ('confermata', 'carrello')
			GROUP BY p.data, p.slot_start, c.capacity";

		$stmt = $this->db->prepare($query);
		$stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
		$stmt->bindParam(':startDay', $startDay, PDO::PARAM_STR);
		$stmt->bindParam(':endDay', $endDay, PDO::PARAM_STR);
		$stmt->execute();
		
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getCourseSlots(int $courseId, string $startDay, string $endDay): array {
		return [];
	}

}