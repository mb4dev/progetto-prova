<?php 

interface JwtTokenManager {
	public function encode() : string;
	public function decode(string $token) ;
}