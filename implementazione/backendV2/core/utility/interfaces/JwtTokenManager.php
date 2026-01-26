<?php

namespace core\utility\interfaces;

interface JwtTokenManager {
	public function encode();
	public function decode();
}