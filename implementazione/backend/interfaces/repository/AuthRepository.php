<?php

abstract class AuthRepository extends Repository {
	abstract public function login(string $email, string $password);
	abstract public function register(string $name, string $email, string $password);
}