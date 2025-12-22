<?php 

abstract class UserRepository extends Repository{
    abstract public function getById(int $id): User;
	abstract public function update(User $user);
}