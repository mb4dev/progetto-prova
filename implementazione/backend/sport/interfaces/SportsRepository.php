<?php

abstract class SportsRepository extends Repository{
	abstract public function getFields() : array;
	abstract public function getCourses() : array;
}