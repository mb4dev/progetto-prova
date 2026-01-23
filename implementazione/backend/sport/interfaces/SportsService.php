<?php

interface SportsService {
	public function getSportsByType(string $type) : Response;
}