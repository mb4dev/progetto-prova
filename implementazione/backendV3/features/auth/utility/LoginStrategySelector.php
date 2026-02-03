<?php

namespace features\auth\utility;

use core\exceptions\CustomException;
use core\factory\Factory;
use core\interfaces\Selector;
use features\auth\strategies\EmailLoginStrategy;

final class LoginStrategySelector implements Selector{
	public function __construct(private Factory $factory){}
	public function select(string $type): object{

		return match($type){
			"email" => $this->factory->get(EmailLoginStrategy::class),
			default => throw new CustomException("tipo $type login sconosciuto", 400)
		};
	}
}