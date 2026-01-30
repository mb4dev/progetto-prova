<?php

namespace features\resources;

use core\interfaces\ResourceService;
use core\interfaces\ResourcesRepository;

final class StandardResourceService implements ResourceService {
	public function __construct(
		private ResourcesRepository $fieldsRepository,
		private ResourcesRepository $coursesRepository
	) {

	}

	public function getAllResourcesByType(ResourceType $type): array {
		return match($type) {
			ResourceType::FIELD => $this->fieldsRepository->getAll(),
			ResourceType::COURSE => $this->coursesRepository->getAll(),
		};
	}
}