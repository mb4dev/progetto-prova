<?php

namespace resources;

use resources\interfaces\FieldsRepository;
use resources\interfaces\CoursesRepository;
use resources\interfaces\ResourceService;

final class StandardResourceService implements ResourceService {
	public function __construct(
		private FieldsRepository $fieldsRepository,
		private CoursesRepository $coursesRepository
	) {

	}

	public function getAllResourcesByType(ResourceType $type): array {
		return match($type) {
			ResourceType::FIELD => $this->fieldsRepository->getFields(),
			ResourceType::COURSE => $this->coursesRepository->getCourses(),
		};
	}
}