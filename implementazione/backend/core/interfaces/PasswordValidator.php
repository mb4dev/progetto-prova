<?php 

interface PasswordValidator {
	public function validate(string $password, string $passwordHash) : bool;
}