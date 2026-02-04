<?php

namespace features\resources\repository;

use core\interfaces\SubscriptionsRepository;
use PDO;
final class PostgreSubscriptionsRepository implements SubscriptionsRepository {
	public function __construct(private PDO $db) {}

	public function getResourceById(int $id): array{
		$query = "SELECT * FROM centro_sportivo.abbonamenti WHERE id=:id";

		$stmt = $this->db->prepare($query);
		$stmt->bindParam(":id", $id);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $rows;
	}

	public function getAll(): array{
		$query = "SELECT * FROM centro_sportivo.abbonamenti";

		$stmt = $this->db->prepare($query);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $rows;
	}
}