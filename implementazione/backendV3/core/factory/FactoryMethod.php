<?php

namespace core\factory;

interface FactoryMethod {
	public function __invoke(Factory $factory);
}