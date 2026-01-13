<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . "/bootstrap.php";


class FieldsRepositoryTest extends TestCase {

	private DefaultFieldsRepository $repo;
	private PDO $connection;

	protected function setUp(): void{
        $this->connection = new PDO('sqlite::memory:');
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->connection->exec("CREATE TABLE IF NOT EXISTS fields (
			id INTEGER PRIMARY KEY AUTOINCREMENT,
			sport TEXT NOT NULL,
			pricePerHour REAL NOT NULL
		)");

        $this->repo = new DefaultFieldsRepository($this->connection);
    }

	public function testGetAllFields(){
		$this->connection->exec("INSERT INTO fields (sport, pricePerHour) VALUES ('test', 10)");

		$fields = $this->repo->getFields();
		$this->assertCount(1, $fields);
	}

	public function testGetBySport(){


	}
}