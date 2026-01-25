<?php

class DefaultBookingsService implements BookingsService {
	private BookingsRepository $repository;

	public function __construct(BookingsRepository $repo) {
		$this->repository = $repo;
}

	public function getOccupiedlots(string $resourceType, int $resourceId, string $startDay) : Response{
		return match($resourceType) {
			ResourceType::CAMPO->value => $this->getOccupiedFieldSlots($resourceId, $startDay),
			ResourceType::CORSO->value => $this->getOccupiedCourseSlots($resourceId, $startDay),
			default => new Response(400, false, ["error" => "..."])
		};
	}

	private function getWeekLastDay(string $startDate): string{
		$date = new DateTime($startDate);
		$date->add(new DateInterval("P7D"));
		return $date->format("Y-m-d");
	}

	private function getOccupiedFieldSlots(string $resourceId, string $startDay) : Response{
		try {
			$endDay = $this->getWeekLastDay($startDay);
			$result = $this->repository->getFieldOccupiedSlots($resourceId, $startDay, $endDay);
			return new Response(200, true, $result);
		}
		catch(Exception $e){
			return new Response(500, false, ["error" => $e->getMessage()]);
		}
	}

	private function getOccupiedCourseSlots(string $resourceId, string $startDay) : Response{
		try {
			$endDay = $this->getWeekLastDay($startDay);
			$rows = $this->repository->getCourseBookingsCount($resourceId, $startDay, $endDay);
			
			$result = [];
			foreach ($rows as $row) {
				if ($row['count'] >= $row['capacity']) {
					$result[$row['data']][] = $row['slot_start'];
				}
			}

			return new Response(200, true, $result);
		}
		catch(Exception $e){
			return new Response(500, false, ["error" => $e->getMessage()]);
		}
	}

}