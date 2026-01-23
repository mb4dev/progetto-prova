<?php

class SportsController extends Controller {

	public function __construct(

		private SportsService $sportsService
	) {
		parent::__construct();
	}


	public function getMiddlewares() : array {
		return [
			AuthMiddleware::class
		];
	}
	

	public function resolveAction(string $action): Response{
		return match (strtolower($action)) {
			"" => $this->getSports(),
			default => new Response(404, false, ["error" => "Action non trovata"])
		};
	}

	private function getSports() : Response{
		if($_SERVER['REQUEST_METHOD'] !== "GET") {
			return new Response(405, false, ["error" => "Metodo non consentito"]);
		}
		$type = $_GET['type'] ?? null;
		if (!$type || !in_array($type, ['campo', 'corso'])) {
			return new Response(400, false, ["error" => "Tipo non valido"]);
		}
		return $this->sportsService->getSportsByType($type);
	}

}