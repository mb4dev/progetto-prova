<?php

namespace resources\interfaces;

use core\utility\interfaces\Repository;

abstract class ResourceRepository extends Repository{
	abstract public function getFields() : array;
	abstract public function getCourses() : array;
}