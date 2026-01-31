<?php

namespace core\utility\interfaces;

use core\model\User;

interface JwtTokenService {
	public function encode(User $user) : string;
	public function decode(string $token) : User;
}