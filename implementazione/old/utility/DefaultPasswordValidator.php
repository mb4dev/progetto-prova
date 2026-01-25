<?php

final class DefaultPasswordValidator implements PasswordValidator {
	public function validate(string $password, string $passwordHash) : bool {
		return password_verify($password, $passwordHash);
	}
}