<?php

namespace resources\interfaces;

use core\utility\interfaces\Repository;

abstract class CoursesRepository extends Repository{
	abstract public function getCourseById(int $id) : array;
	abstract public function getCourses() : array;
}