<?php

class DefaultAuthRepository extends AuthRepository {

	public function __construct(PDO $connection) {
		parent::__construct($connection);
	}

	public function login(string $username, string $password) {
		
	}

	public function register(string $name, string $username, string $password) {
		$stmt = $this->connection->prepare("SELECT id FROM users WHERE username = ?");
		$stmt->execute([$username]);
		if ($stmt->fetch()) {
			return false;
		}

		$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
		$stmt = $this->connection->prepare("INSERT INTO users (name, username, password) VALUES (?, ?, ?)");
		return $stmt->execute([$name, $username, $hashedPassword]);
		
		
	}
}