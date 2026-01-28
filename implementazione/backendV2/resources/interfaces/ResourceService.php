<?php

namespace resources\interfaces;

use resources\ResourceType;

interface ResourceService {
	public function getResourceByType(ResourceType $type) : array;
}