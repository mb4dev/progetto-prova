<?php

namespace core\utility;

use core\interfaces\PasswordManager;

class DefaultPasswordManager implements PasswordManager {

	public function validate(string $password, string $hashedPassword): bool{	
		return password_verify($password, $hashedPassword);
	}

	public function hash(string $password): string{
		return password_hash($password, PASSWORD_DEFAULT, 	["cost" => 10]);
	}
}