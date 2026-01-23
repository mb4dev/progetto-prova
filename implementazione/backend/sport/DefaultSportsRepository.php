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
		$query = 'SELECT * FROM centro_sportivo.corsi';
		$stmt = $this->db->prepare($query);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

}
