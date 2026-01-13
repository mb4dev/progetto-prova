<?php

class FieldsController extends Controller {

	public function __construct(
		
		private FieldsService $fieldsService
	) {
		parent::__construct();
	}


	public function getMiddlewares() : array {
		return [];
	}
	

	public function resolveAction(string $action): Response{
		return match (strtolower($action)) {
			"" => $this->getFields(),
			default => new Response(404, false, ["error" => "Action non trovata"])
		};
	}

	private function getFields() : Response{
		if($_SERVER['REQUEST_METHOD'] !== "GET") {
			return new Response(405, false, ["error" => "Metodo non consentito"]);
		}
		return $this->fieldsService->getFields();
	}

}