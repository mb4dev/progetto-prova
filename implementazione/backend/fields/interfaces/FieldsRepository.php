<?php

abstract class FieldsRepository extends Repository{
	abstract public function getFields() : array;
}