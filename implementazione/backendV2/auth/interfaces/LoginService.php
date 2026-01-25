<?php

namespace auth\interfaces;

use core\http\Response;
use core\model\Role;

interface AuthService {
	public function login(string $email, string $password) : Response;
	public function register(string $name, string $email, string $password, Role $role = Role::USER) : Response;
}