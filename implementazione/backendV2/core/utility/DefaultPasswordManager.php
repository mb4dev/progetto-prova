<?php

namespace core\utility;

use core\utility\interfaces\PasswordManager;

class DefaultPasswordManager implements PasswordManager {

	public function validate(string $password, string $hashedPassword): bool{	
		return password_verify(password_hash($password, PASSWORD_DEFAULT), $hashedPassword);
	}

	public function hash(string $password): string{
		return password_hash($password, PASSWORD_DEFAULT);
	}
}