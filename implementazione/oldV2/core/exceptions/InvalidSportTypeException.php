<?php 

namespace core\exceptions;

class InvalidSportTypeException extends CustomException {
	public function __construct() {
		parent::__construct("parametro 'type' non valido", 400);
	}
}
