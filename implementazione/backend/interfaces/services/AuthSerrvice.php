<?php

interface AuthService {
	public function login(string $username, string $password) : Response;
	public function register(string $name, string $username, string $password) : Response;
}