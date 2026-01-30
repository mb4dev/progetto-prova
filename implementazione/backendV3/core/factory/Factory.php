<?php

namespace core\factory;

use core\exceptions\CustomException;

final class Factory {
	private array $factories = [];
	private array $instances = [];

	public function __construct()	{
	}

	public function register(string $className, FactoryMethod $factory){
		$this->factories[$className] = $factory;
	}

	/**
	 * @template T of object
	 * @param class-string<T> $className
	 * @return T
	 */
	public function get(string $className) : object {
		echo "richiesto: $className\n";
		if(isset($this->instances[$className])) return $this->instances[$className];

		if(!isset($this->factories[$className])) throw new CustomException("Nessun servizio di creazione registrato per $className", 500);

		$instance = ($this->factories[$className])($this);

		$this->instances[$className] = $instance;
		return $instance;
	}
}