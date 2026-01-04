<?php

class Field {
	public function __construct(
		public int $id,
		public string $sport,
		public float $pricePerHour
	) {}
}