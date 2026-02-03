<?php

namespace features\auth\utility;

use core\exceptions\CustomException;
use core\factory\Factory;
use core\interfaces\Selector;
use features\auth\strategies\EmailRegisterStrategy;

final class RegisterStrategySelector implements Selector{
	public function __construct(private Factory $factory){}
	public function select(string $type): object{

		return match($type){
			"email" => $this->factory->get(EmailRegisterStrategy::class),
			default => throw new CustomException("tipo $type login sconosciuto", 400)
		};
	}
}