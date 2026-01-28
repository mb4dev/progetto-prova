<?php

namespace auth\interfaces;

use core\model\Role;
use core\model\User;
use core\utility\interfaces\Repository;

abstract class AuthRepository extends Repository {
	abstract public function getUserById(int $id) : User;
	abstract public function login(string $email, string $password) : User;
	abstract public function register(string $name, string $email, string $password, Role $role = Role::USER) : User;
}