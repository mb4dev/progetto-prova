<?php 

namespace core\utility;
use core\interfaces\ResponseStrategy;
use core\http\Response;

class ConsoleResponseStrategy implements ResponseStrategy {

	public function __construct() {
	}

    public function response(Response $response): void {
		var_dump($response);
    }
}
