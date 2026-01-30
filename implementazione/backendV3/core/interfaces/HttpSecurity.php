<?php

namespace core\interfaces;

use core\model\User;

interface HttpSecurity {

	public function authenticate(string $token) : ?User;

    //public function authorize(string $token): bool;
}