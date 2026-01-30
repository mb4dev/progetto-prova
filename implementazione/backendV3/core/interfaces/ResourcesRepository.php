<?php

namespace core\interfaces;

interface ResourcesRepository {
	public function getResourceById(int $id) : array;
	public function getAll() : array;
}