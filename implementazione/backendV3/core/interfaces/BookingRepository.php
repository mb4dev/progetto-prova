<?php

namespace core\interfaces;

use features\booking\BookingState;

interface BookingRepository
{
    public function getOccupiedSlots(int $resourceId, string $date): array;
    public function insertBooking(
        int $userId,
        int $resourceId,
        string $date,
        string $slot,
    ): int;

    public function getBooking(int $id): array;
    public function updateBooking(int $id, BookingState $newState): bool;
}
