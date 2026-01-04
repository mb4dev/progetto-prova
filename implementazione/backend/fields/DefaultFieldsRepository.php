<?php

final class DefaultFieldsRepository extends FieldsRepository {

	public function __construct(PDO $connection) {
		parent::__construct($connection);
	}

	public function getFields() : array {
		$query = "SELECT * FROM fields";
		$stmt = $this->db->prepare($query);
		$stmt->execute();
		return array_map(fn($row) => new Field(
			(int) $row['id'],
			$row['sport'],
			(float) $row['pricePerHour']
		), $stmt->fetchAll(PDO::FETCH_ASSOC));
	}

}
