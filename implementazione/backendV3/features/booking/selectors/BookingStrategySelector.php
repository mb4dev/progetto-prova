<?php

namespace features\booking\selectors;

use core\exceptions\CustomException;
use core\factory\Factory;
use core\interfaces\BookingStrategy;
use core\interfaces\Selector;
use features\booking\strategies\CourseBookingStrategy;
use features\booking\strategies\FieldBookingStrategy;

class BookingStrategySelector implements Selector {

	public function __construct(private Factory $factory) {}

	public function select(string $type): BookingStrategy {
		return match($type) {
			'campo' => $this->factory->get(FieldBookingStrategy::class),
			'corso' => $this->factory->get(CourseBookingStrategy::class),
			default => throw new CustomException("Tipo di booking non supportato: $type", 400)
		};
	}
}
