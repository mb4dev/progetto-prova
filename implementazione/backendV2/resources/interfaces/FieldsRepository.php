<?php

namespace resources\interfaces;

use core\utility\interfaces\Repository;

abstract class FieldsRepository extends Repository{
	abstract public function getFieldById(int $id) : array;
	abstract public function getFields() : array;
}