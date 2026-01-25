<?php

namespace commands\sports;

use core\interfaces\Command;
use core\model\Response;
use sport\interfaces\SportsService;
use sport\DefaultSportsService;
use sport\DefaultSportsRepository;

class GetSportsCommand implements Command
{
    private SportsService $sportsService;

    public function __construct()
    {
        $repository = new DefaultSportsRepository();
        $this->sportsService = new DefaultSportsService($repository);
    }

    public function execute(array $params, array $query = []): Response
    {
        // Validate type parameter
        $type = $query['type'] ?? null;
        if (!$type || !in_array($type, ['campo', 'corso'])) {
            return new Response(400, false, ["error" => "Tipo non valido"]);
        }

        return $this->sportsService->getSportsByType($type);
    }

    public function validateHttpMethod(string $method): bool
    {
        return $method === 'GET';
    }

    public function getRequiredParameters(): array
    {
        return [];
    }

    public function getOptionalParameters(): array
    {
        return [
            'type' => null // Will be read from query parameters
        ];
    }

    public function getDescription(): string
    {
        return 'Get sports by type (campo or corso)';
    }
}