<?php

class DefaultUserRepository extends UserRepository {
    
	public function __construct(PDO $connection) {
		parent::__construct($connection);

		$this->db->exec("CREATE TABLE IF NOT EXISTS users (
			id INTEGER PRIMARY KEY AUTOINCREMENT,
			name TEXT NOT NULL,
			email TEXT NOT NULL UNIQUE,
			password TEXT NOT NULL
		)");
	}

	public function getById(int $id): User {
		$stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
		$stmt->execute(["id" => $id]);
		$userData = $stmt->fetch(PDO::FETCH_ASSOC); 
	
		if($userData === false) throw new UserNotFoundException();

		return new User($userData["id"], $userData["name"], $userData["email"], $userData["password"]);
	}

	public function update(User $user) {
		$stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
		$stmt->execute(["id" => $user->id]);

		if($stmt->fetch() === false) throw new UserNotFoundException();

		$stmt = $this->db->prepare("UPDATE users SET name = :name, email = :email WHERE id = :id");
		$stmt->execute([
			"id" => $user->id,
			"name" => $user->name,
			"email" => $user->email
		]);
	}
}