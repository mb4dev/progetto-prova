<?php

class DefaultSportsService implements SportsService {
	private SportsRepository $repository;
	public function __construct(private SportsRepository $repo) {
		$this->repository = $repo;
	}
	public function getSportsByType(string $type) : Response{
		try {
			if ($type === 'campo') {
				$sports = $this->repository->getFields();
			} elseif ($type === 'corso') {
				$sports = $this->repository->getCourses();
			} else {
				return new Response(400, false, ["error" => "Tipo non valido"]);
			}
			return new Response(200, true, $sports);
		}
		catch(Exception $e){
			return new Response(500, false, ["error" => $e->getMessage()]);
		}
	}

}
