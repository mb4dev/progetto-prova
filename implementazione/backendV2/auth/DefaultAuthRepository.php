<?php

namespace auth;

use auth\interfaces\AuthRepository;
use core\exceptions\UserAlreadyExistsException;
use core\exceptions\UserNotFoundException;
use core\model\Role;
use core\model\User;
use PDO;

class DefaultAuthRepository extends AuthRepository {

	public function __construct(PDO $connection) {
		parent::__construct($connection);
	}

	public function login(string $email, string $password) : User{
		$stmt = $this->db->prepare("SELECT * FROM centro_sportivo.utenti WHERE email = ?");
		$stmt->execute([$email]);

		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($user === false) {
			throw new UserNotFoundException();
		}
		return new User($user["id"], $user["name"], $user["email"], $user["password"], Role::from($user["role"]));
	}

	public function register(string $name, string $email, string $password, Role $role = Role::USER) : User{
		$stmt = $this->db->prepare("SELECT id FROM centro_sportivo.utenti WHERE email = ?");
		$stmt->execute([$email]);
		if ($stmt->fetch()) {
			throw new UserAlreadyExistsException();
		}

		$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
		$stmt = $this->db->prepare("INSERT INTO centro_sportivo.utenti (name, email, password, role) VALUES (?, ?, ?, ?)");
		$stmt->execute([$name, $email, $hashedPassword, $role->value]);
		return new User($this->db->lastInsertId(), $name, $email, $hashedPassword, $role);
	}
}

