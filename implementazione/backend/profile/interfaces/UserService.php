<?php

interface UserService {
	public function getById(int $id): Response;
	public function update(User $user): Response;
}