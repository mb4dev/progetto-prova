<?php

namespace core\interfaces;

use core\model\User;

interface TokenService {
	public function encode(User $user) : string;
	public function decode(string $token) : User;
}