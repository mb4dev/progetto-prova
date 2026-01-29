<?php

namespace core\utility\jwt;
use core\exceptions\InvalidTokenException;
use core\model\Role;
use core\model\User;
use core\utility\interfaces\JwtTokenService;

final class MyJwtService implements JwtTokenService {

	public function __construct(
		private string $secret = "chiave_segreta_256_bit_temporanea", 
		private JwtAlgorithm $algorithm = JwtAlgorithm::HS256, 
		private int $expirationSeconds = 3600) {}
	public function encode(User $user) : string{

		$header = [
			"alg" => $this->algorithm->name,
			"typ" => "JWT"
		];
		$encodedHeader = $this->base64UrlEncode(json_encode($header));

		$issuedAt = time();
		$expiresAt = $issuedAt + $this->expirationSeconds;

		$payload = [
			"iss" => "centro_sportivo",
			"iat" => $issuedAt,
			"exp" => $expiresAt,
			"sub" => $user->id,
			"data" => [
				"id" => $user->id,
				"name" => $user->name,
				"email" => $user->email,
				"role" => $user->role->value,
			]
		];


		$encodedPayload = $this->base64UrlEncode(json_encode($payload));

		$signature = $this->sign($encodedHeader . '.' . $encodedPayload);

		return "$encodedHeader.$encodedPayload.$signature";
	}

	public function decode(string $token): User{
		$parts = explode(".", $token);

		if(count($parts) !== 3) throw new InvalidTokenException('Formato token non valido', 401);
		
		[$encodedHeader, $encodedPayload, $encodedSignature] = $parts;

		$expectedSignature = $this->sign($encodedHeader . '.' . $encodedPayload);
		if(!hash_equals($expectedSignature, $encodedSignature)) throw new InvalidTokenException('Firma token non valida', 401);

		$payloadJson = $this->base64UrlDecode($encodedPayload);
		$payload = json_decode($payloadJson, true);

		if (!$payload) throw new InvalidTokenException('Payload token non valido', 401);
		
		if (isset($payload['exp']) && time() > $payload['exp']) throw new InvalidTokenException('Token scaduto', 401);
		
		if (!isset($payload['data'])) throw new InvalidTokenException('Token malformato: dati mancanti', 401);
		
				$userData = $payload['data'];
		
		return new User(
			id: $userData['id'],
			name: $userData['name'],
			email: $userData['email'],
			password: '',  
			role: Role::from($userData['role'])
		);
	}

	private function sign(string $data){
		$hash = hash_hmac($this->algorithm->value, $data, $this->secret, true);

		return $this->base64UrlEncode($hash);
	}

	private function base64UrlEncode(string $data): string {
		$base64 = base64_encode($data);
		$base64Url = strtr($base64, '+/', '-_');
		return rtrim($base64Url, '=');
	}

	private function base64UrlDecode(string $data): string {
		$base64 = strtr($data, '-_', '+/');
		$padding = strlen($data) % 4;
		
		if ($padding) {
			$base64 .= str_repeat('=', 4 - $padding);
		}
		
		$decoded = base64_decode($base64);
		
		if ($decoded === false) throw new InvalidTokenException('Decodifica base64 fallita', 401);
		
		return $decoded;
	}
}