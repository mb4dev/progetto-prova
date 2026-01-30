<?php

namespace core\interfaces;

use core\model\Role;
use core\model\User;

interface AuthRepository  {
	public function getUserById(int $id) : User;
	public function login(string $email, string $password) : User;
	public function register(string $name, string $email, string $password, Role $role = Role::USER) : User;
}