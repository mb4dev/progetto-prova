<?php

namespace resources\interfaces;

use resources\ResourceType;

interface ResourceService {
	public function getAllResourcesByType(ResourceType $type) : array;
}