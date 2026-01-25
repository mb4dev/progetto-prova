<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . "/bootstrap.php";


class AuthServiceTest extends TestCase {

    public function testLoginSuccess(): void {
        $mockRepo = $this->createMock(AuthRepository::class);
        $mockRepo->method('login')
            ->with('test@test.com', 'password')
            ->willReturn(new User(1, "test", "test@test.com", "hashed_password"));

        $mockPasswordValidator = $this->createMock(PasswordValidator::class);
        $mockPasswordValidator->method('validate')
            ->with('password', 'hashed_password')
            ->willReturn(true);

        $mockTokenManager = $this->createMock(JwtTokenManager::class);
        $mockTokenManager->method('encode')
            ->willReturn('mock_token');

        $service = new DefaultAuthService($mockRepo, $mockPasswordValidator, $mockTokenManager);

        $response = $service->login("test@test.com", "password");

        $this->assertEquals(200, $response->code);
        $this->assertTrue($response->success);
        $this->assertEquals("Login effettuato", $response->jsonData["message"]);
        $this->assertEquals("mock_token", $response->jsonData["token"]);
    }

    public function testLoginWrongPassword(): void {
        $mockRepo = $this->createMock(AuthRepository::class);
        $mockRepo->method('login')
            ->with('test@test.com', 'password')
            ->willReturn(new User(1, "test", "test@test.com", "hashed_password"));

        $mockPasswordValidator = $this->createMock(PasswordValidator::class);
        $mockPasswordValidator->method('validate')
            ->with('password', 'hashed_password')
            ->willReturn(false);

        $mockTokenManager = $this->createMock(JwtTokenManager::class);

        $service = new DefaultAuthService($mockRepo, $mockPasswordValidator, $mockTokenManager);

        $response = $service->login("test@test.com", "password");

        $this->assertEquals(401, $response->code);
        $this->assertFalse($response->success);
        $this->assertEquals("Password non valida", $response->jsonData["error"]);
    }

    public function testLoginUserNotFound(): void {
        $mockRepo = $this->createMock(AuthRepository::class);
        $mockRepo->method('login')
            ->with('test@test.com', 'password')
            ->willThrowException(new UserNotFoundException());

        $mockPasswordValidator = $this->createMock(PasswordValidator::class);
        $mockTokenManager = $this->createMock(JwtTokenManager::class);

        $service = new DefaultAuthService($mockRepo, $mockPasswordValidator, $mockTokenManager);

        $response = $service->login("test@test.com", "password");

        $this->assertEquals(400, $response->code);
        $this->assertFalse($response->success);
        $this->assertEquals("Utente non trovato", $response->jsonData["error"]);
    }

    public function testRegisterSuccess(): void {
        $mockRepo = $this->createMock(AuthRepository::class);
        $mockRepo->method('register')
            ->with('Test User', 'test@test.com', 'password')
            ->willReturn(new User(1, "Test User", "test@test.com", "hashed_password"));

        $mockPasswordValidator = $this->createMock(PasswordValidator::class);

        $mockTokenManager = $this->createMock(JwtTokenManager::class);
        $mockTokenManager->method('encode')
            ->willReturn('mock_token');

        $service = new DefaultAuthService($mockRepo, $mockPasswordValidator, $mockTokenManager);

        $response = $service->register("Test User", "test@test.com", "password");

        $this->assertEquals(200, $response->code);
        $this->assertTrue($response->success);
        $this->assertEquals("Registrazione effettuata", $response->jsonData["message"]);
        $this->assertEquals("mock_token", $response->jsonData["token"]);
    }

    public function testRegisterUserAlreadyExists(): void {
        $mockRepo = $this->createMock(AuthRepository::class);
        $mockRepo->method('register')
            ->with('Test User', 'test@test.com', 'password')
            ->willThrowException(new UserAlreadyExistsException());

        $mockPasswordValidator = $this->createMock(PasswordValidator::class);
        $mockTokenManager = $this->createMock(JwtTokenManager::class);

        $service = new DefaultAuthService($mockRepo, $mockPasswordValidator, $mockTokenManager);

        $response = $service->register("Test User", "test@test.com", "password");

        $this->assertEquals(400, $response->code);
        $this->assertFalse($response->success);
        $this->assertEquals("Utente già registrato", $response->jsonData["error"]);
    }
}