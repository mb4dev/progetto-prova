<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . "/bootstrap.php";


class UserRepositoryTest extends TestCase {


	private DefaultUserRepository $userRepository;
	private PDO $connection;

	protected function setUp(): void{
        $this->connection = new PDO('sqlite::memory:');
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->connection->exec("
            CREATE TABLE users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                email TEXT NOT NULL UNIQUE,
                password TEXT NOT NULL
            )
        ");

        $this->repo = new DefaultUserRepository($this->connection);
    }

	public function testGetById() {
		$this->connection->exec("INSERT INTO users (name, email, password) VALUES ('test', 'test@test.com', 'test')");

		$user = $this->repo->getById(1);

		$this->assertEquals(1, $user->id);
		$this->assertEquals("test", $user->name);
		$this->assertEquals("test@test.com", $user->email);
	}

	public function testGetByIdNotFound() {
		$this->expectException(UserNotFoundException::class);
		$this->repo->getById(1);
	}

	public function testUpdate(){
		$this->connection->exec("INSERT INTO users (name, email, password) VALUES ('toupdate', 'test@test.com', 'test')");
		$user = $this->repo->getById(1);


		$updatedName = "updated";
		$expected = new User($user->id, $updatedName, $user->email, $user->password);
		$this->repo->update($expected);

		$got = $this->repo->getById(1);
		
		$this->assertEquals($expected->id, $got->id);
		$this->assertEquals($expected->name, $got->name);
		$this->assertEquals($expected->email, $got->email);
	}

	public function testUpdateNotFound(){
		$this->expectException(UserNotFoundException::class);
		$this->repo->update(new User(1, "test", "test@test.com", "test"));
	}

}