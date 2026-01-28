<?php

namespace resources;

use PDO;
use resources\interfaces\CoursesRepository;
use core\exceptions\ResourceNotFoundException;

final class PostgreCoursesRepository extends CoursesRepository {
	public function __construct(PDO $connection) {
		parent::__construct($connection);
	}

	public function getCourses(): array {
		$query = '
			SELECT c.*, oc.orario 
			FROM centro_sportivo.corsi c
			LEFT JOIN centro_sportivo.orari_corsi oc ON c.id = oc.corso_id
			ORDER BY c.id, oc.orario
		';
		$stmt = $this->db->prepare($query);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $this->mapRowsToCourses($rows);
	}

	public function getCourseById(int $id): array {
		$query = '
			SELECT c.*, oc.orario 
			FROM centro_sportivo.corsi c
			LEFT JOIN centro_sportivo.orari_corsi oc ON c.id = oc.corso_id
			WHERE c.id = ?
			ORDER BY oc.orario
		';
		$stmt = $this->db->prepare($query);
		$stmt->execute([$id]);
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if (empty($rows)) {
			throw new ResourceNotFoundException("corso $id non esistente");
		}

		$courses = $this->mapRowsToCourses($rows);
		return $courses[0];
	}

	private function mapRowsToCourses(array $rows): array {
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