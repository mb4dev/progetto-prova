<?php

interface BookingsService {
	//public function addFieldToCart(int $userId, int $fieldId, string $data, string $slotStart) : Response;
	//public function checkoutFields(int $userId, array $bookingIds) : Response;
	//public function bookCourse(int $userId, int $courseId, string $data, string $slotStart, int $quantity = 1) : Response;
	public function getOccupiedlots(string $resourceType, int $resourceId, string $startDay) : Response;
}