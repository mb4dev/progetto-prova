<?php

abstract class Repository {
	protected $db;
	public function __construct(PDO $connection) {
		$this->db = $connection;
	}
}