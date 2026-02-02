<?php

namespace core\interfaces;
interface Strategy {
	public function execute(array $params): array ;
}