<?php

abstract class AuthRepository extends Repository {
	abstract public function login(string $username, string $password);
	abstract public function register(string $name, string $username, string $password);
}