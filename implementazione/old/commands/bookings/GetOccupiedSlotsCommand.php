<?php

namespace commands\bookings;

use core\interfaces\Command;
use core\model\Response;
use bookings\interfaces\BookingsService;
use bookings\DefaultBookingsService;
use bookings\DefaultBookingsRepository;

class GetOccupiedSlotsCommand implements Command
{
    private BookingsService $bookingsService;

    public function __construct()
    {
        $repository = new DefaultBookingsRepository();
        $this->bookingsService = new DefaultBookingsService($repository);
    }

    public function execute(array $params, array $query = []): Response
    {
        // Validate resource_type
        $resourceType = \ResourceType::tryFrom($params['resource_type']);
        if (!$resourceType) {
            return new Response(400, false, ["error" => "Tipo risorsa non valido"]);
        }

        return $this->bookingsService->getOccupiedlots(
            $resourceType->value, 
            $params['resource_id'], 
            $params['start_day']
        );
    }

    public function validateHttpMethod(string $method): bool
    {
        return $method === 'POST';
    }

    public function getRequiredParameters(): array
    {
        return ['resource_id', 'resource_type', 'start_day'];
    }

    public function getOptionalParameters(): array
    {
        return [];
    }

    public function getDescription(): string
    {
        return 'Get occupied slots for a specific resource';
    }
}