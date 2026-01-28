<?php

namespace resources;

use resources\interfaces\ResourceRepository;
use resources\interfaces\ResourceService;

final class StandardResourceService implements ResourceService {
	public function __construct(private ResourceRepository $repository) {

	}

	public function getResourceByType(ResourceType $type) : array{
	
		return match($type){
			ResourceType::FIELD => $this->repository->getFields(),
			ResourceType::COURSE => $this->repository->getCourses(),
		};
	}
}