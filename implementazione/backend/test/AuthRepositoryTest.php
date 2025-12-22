<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . "/bootstrap.php";


class DefaultAuthRepositoryTest extends TestCase
{
    private DefaultAuthRepository $repo;

    protected function setUp(): void{
        $connection = new PDO('sqlite::memory:');
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $connection->exec("
            CREATE TABLE users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                email TEXT NOT NULL UNIQUE,
                password TEXT NOT NULL
            )
        ");

        $this->repo = new DefaultAuthRepository($connection);
    }

    public function testRegisterSuccess(): void{
        $user = $this->repo->register("test", "test@example.com", "test");

        $this->assertNotNull($user->id);
        $this->assertSame("test", $user->name);
        $this->assertSame("test@example.com", $user->email);
        $this->assertTrue(password_verify("test", $user->password));
    }

    public function testRegisterDuplicateEmailThrowsException(): void{
        $this->repo->register("test", "test@example.com", "test");

        $this->expectException(UserAlreadyExistsException::class);
        $this->repo->register("test", "test@example.com", "test");
    }

    public function testLoginSuccess(): void{
        $userRegistered = $this->repo->register("test", "test@example.com", "test");

        $userLogged = $this->repo->login("test@example.com", "test");

        $this->assertSame($userRegistered->id, $userLogged->id);
        $this->assertSame($userRegistered->email, $userLogged->email);
    }

    public function testLoginNonExistingUserThrowsException(): void{
        $this->expectException(UserNotFoundException::class);
        $this->repo->login("non_esistente@example.com", "test");
    }
}
