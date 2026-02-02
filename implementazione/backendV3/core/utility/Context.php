<?php

namespace core\utility;
use core\exceptions\CustomException;
use core\interfaces\Strategy;

final class Context { 
	private ?Strategy $strategy = null;

	public function setStrategy(Strategy $strategy) : void {
		$this->strategy = $strategy;
	}

	public function execute(array $params) : array{
		if ($this->strategy === null) {
            throw new CustomException("Nessuna strategia impostata", 500);
        }
		return $this->strategy->execute($params);
	}

}