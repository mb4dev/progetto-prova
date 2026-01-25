<?php

final class DefaultSportsRepository extends SportsRepository {

	public function __construct(PDO $connection) {
		parent::__construct($connection);
	}

	public function getFields() : array {
		$query = 'SELECT * FROM centro_sportivo.campi';
		$stmt = $this->db->prepare($query);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getCourses() : array {
		$query = '
			SELECT c.*, oc.orario 
			FROM centro_sportivo.corsi c
			LEFT JOIN centro_sportivo.orari_corsi oc ON c.id = oc.corso_id
			ORDER BY c.id, oc.orario
		';
		$stmt = $this->db->prepare($query);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$courses = [];
		foreach ($rows as $row) {
			$id = $row['id'];
			if (!isset($courses[$id])) {
				$courses[$id] = [
					'id' => $row['id'],
					'name' => $row['name'],
					'description' => $row['description'],
					'price' => $row['price'],
					'capacity' => $row['capacity'],
					'schedule' => []
				];
			}
			
			if ($row['orario']) {
				$courses[$id]['schedule'][] = substr($row['orario'], 0, 5);
			}
		}

		return array_values($courses);
	}

}
