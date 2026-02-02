<?php

namespace features\auth\repository;

use core\interfaces\AuthRepository;
use core\exceptions\CustomException;
use core\model\Role;
use core\model\User;
use PDO;

class PostgreAuthRepository implements AuthRepository {

	public function __construct(private PDO $db) {}

	public function getUserById(int $id): User{
		$stmt = $this->db->prepare("SELECT * FROM centro_sportivo.utenti WHERE id = ?");
		$stmt->execute([$id]);
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($user === false)
			throw new CustomException("utente $id non esistente", 404);
		
		return new User($user["id"], $user["name"], $user["email"], $user["password"], Role::from($user["role"]));
	}

	public function login(string $email, string $password) : User{
		$stmt = $this->db->prepare("SELECT * FROM centro_sportivo.utenti WHERE email = ?");
		$stmt->execute([$email]);
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($user === false)
			throw new CustomException("utente non esistente", 404);
		
		return new User($user["id"], $user["name"], $user["email"], $user["password"], Role::from($user["role"]));
	}

	public function register(string $name, string $email, string $password, Role $role = Role::USER) : User{
		$stmt = $this->db->prepare("SELECT id FROM centro_sportivo.utenti WHERE email = ?");
		$stmt->execute([$email]);
		if ($stmt->fetch()) 
			throw new CustomException("utente già registrato", 409);

		$stmt = $this->db->prepare("INSERT INTO centro_sportivo.utenti (name, email, password, role) VALUES (?, ?, ?, ?)");
		$stmt->execute([$name, $email, $password, $role->value]);
		return new User($this->db->lastInsertId(), $name, $email, $password, $role);
	}
}
