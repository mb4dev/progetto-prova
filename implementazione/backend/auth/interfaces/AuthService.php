<?php

interface AuthService {
	public function login(string $email, string $password) : Response;
	public function register(string $name, string $email, string $password) : Response;
}