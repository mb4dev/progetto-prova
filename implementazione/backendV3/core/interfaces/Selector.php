<?php

namespace core\interfaces;

interface Selector {
	public function select(string $type) : object;
}