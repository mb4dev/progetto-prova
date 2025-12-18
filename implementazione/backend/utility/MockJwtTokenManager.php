<?php

class MockJwtTokenManager implements JwtTokenManager {
	public function encode() : string {
		return "mock_token";
	}
	public function decode(string $token) {
		return true;
	}
}