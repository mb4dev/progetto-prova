<?php

interface FieldsService {

	public function getFields() : Response;
	public function getSlotsForWeek(int $idCampo, string $startDay) : Response;
}