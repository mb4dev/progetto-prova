<?php

namespace auth\interfaces;

use core\model\Role;

interface AuthService {
	public function login(string $email, string $password) : array;
	public function register(string $name, string $email, string $password, Role $role = Role::USER) : array;
}