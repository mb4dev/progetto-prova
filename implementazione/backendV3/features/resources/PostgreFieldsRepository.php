<?php

namespace features\resources;

use core\exceptions\CustomException;
use core\interfaces\FieldsRepository;
use core\interfaces\ResourcesRepository;
use PDO;

final class PostgreFieldsRepository implements FieldsRepository {
	public function __construct(private PDO $db) {
	}

	public function getAll(): array {
		$query = 'SELECT * FROM centro_sportivo.campi';
		$stmt = $this->db->prepare($query);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getResourceById(int $id): array {
		$query = 'SELECT * FROM centro_sportivo.campi WHERE id = ?';
		$stmt = $this->db->prepare($query);
		$stmt->execute([$id]);
		$field = $stmt->fetch(PDO::FETCH_ASSOC);

		if (!$field) 
			throw new CustomException("campo $id non esistente", 404);
	
		return $field;
	}
}