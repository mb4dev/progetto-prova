<?php

namespace resources;

use PDO;
use resources\interfaces\FieldsRepository;
use core\exceptions\ResourceNotFoundException;
use core\utility\interfaces\Repository;

final class PostgreFieldsRepository extends FieldsRepository {
	public function __construct(PDO $connection) {
		parent::__construct($connection);
	}

	public function getFields(): array {
		$query = 'SELECT * FROM centro_sportivo.campi';
		$stmt = $this->db->prepare($query);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getFieldById(int $id): array {
		$query = 'SELECT * FROM centro_sportivo.campi WHERE id = ?';
		$stmt = $this->db->prepare($query);
		$stmt->execute([$id]);
		$field = $stmt->fetch(PDO::FETCH_ASSOC);

		if (!$field) {
			throw new ResourceNotFoundException();
		}

		return $field;
	}
}