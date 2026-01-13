<?php

class DefaultAuthRepository extends AuthRepository {

	public function __construct(PDO $connection) {
		parent::__construct($connection);
/*
		$this->db->exec("CREATE TABLE IF NOT EXISTS users (
			id INTEGER PRIMARY KEY AUTOINCREMENT,
			name TEXT NOT NULL,
			email TEXT NOT NULL UNIQUE,
			password TEXT NOT NULL
		)");
*/
	}

	public function login(string $email, string $password) : User{
		$stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
		$stmt->execute([$email]);

		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($user === false) {
			throw new UserNotFoundException();
		}
		return new User($user["id"], $user["name"], $user["email"], $user["password"]);
	}

	public function register(string $name, string $email, string $password) : User {
		$stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
		$stmt->execute([$email]);
		if ($stmt->fetch()) {
			throw new UserAlreadyExistsException();
		}

		$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
		$stmt = $this->db->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
		$stmt->execute([$name, $email, $hashedPassword]);
		return new User($this->db->lastInsertId(), $name, $email, $hashedPassword);
	}
}

