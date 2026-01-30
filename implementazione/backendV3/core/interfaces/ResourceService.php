<?php 

namespace core\interfaces;

use features\resources\ResourceType;

interface ResourceService {
	public function getAllResourcesByType(ResourceType $type): array;

}