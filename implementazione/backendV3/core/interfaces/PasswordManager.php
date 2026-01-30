<?php

namespace core\interfaces;


interface PasswordManager {
	public function validate(string $password, string $hashedPassword) : bool;
	public function hash(string $password) : string;


}