<?php
/*
namespace core\utility;
abstract class AbstractRegistry {
	protected array $items = [];
	protected string $errorMessage = "Elemento non registrato";

	public function register(string $type, callable $factory): void {
        $this->items[$type] = $factory;
    }

	public function get(string $type, Factory $factory): Strategy {
        if (!isset($this->strategies[$type])) {
            throw new CustomException("Tipo di registrazione non supportato: $type", 400);
        }

        return ($this->strategies[$type])($factory);
    }
}
	*/ 	