<?php

namespace core\di;

use core\exceptions\CustomException;

final class Container {
	private array $factories = [];
	private array $instances = [];

	public function __construct()	{
	}

	public function register(string $className, callable $factory) {
		$this->factories[$className] = $factory;
	}

	public function get(string $className) : object {
		echo "richiesto: $className<br>";
		if(isset($this->instances[$className])) return $this->instances[$className];

		if(!isset($this->factories[$className])) throw new CustomException("Nessun servizio di creazione registrato per $className", 500);

		$instance = ($this->factories[$className])($this);

		$this->instances[$className] = $instance;
		return $instance;
	}
}