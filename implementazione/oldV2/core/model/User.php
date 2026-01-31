<?php

namespace core\model;
final class User {
	public function __construct(
		public int $id, 
		public string $name, 
		public string $email, 
		public string $password,
		public Role $role
	) {}
}