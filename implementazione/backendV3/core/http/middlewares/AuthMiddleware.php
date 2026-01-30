<?php

use core\interfaces\HttpSecurity;
use core\model\User;

final class AuthMiddleware implements HttpSecurity {

	//token service, auth repository
	public function authenticate(string $token) :?User{
		throw new \Exception('Not implemented');
	}

	/*
	public function authorize(string $token): bool{
		throw new \Exception('Not implemented');
	}
	*/
}