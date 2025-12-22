<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . "/bootstrap.php";


class UserServiceTest extends TestCase {
	
	private UserRepository $userRepository;
	private DefaultUserService $userService;
	
	
	protected function setUp(): void {
		$this->userRepository = $this->createMock(UserRepository::class);
		$this->userService = new DefaultUserService($this->userRepository);
	}
	
	public function testGetByIdSuccess(): void {
		$expectedUser = new User(1, "test", "test@test.com", "hashed_password");

		$this->UserRepository->method('getById')
			->with(1)
			->willReturn($expectedUser);
		
		$response = $this->userService->getById(1);
		
		$this->assertEquals(200, $response->code);
		$this->assertTrue($response->success);
		$this->assertEquals($expectedUser->name, $response->jsonData["user"]->name);
	}
	
	public function testGetByIdNotFound(): void {
		$this->UserRepository->method('getById')
			->with(1)
			->willThrowException(new UserNotFoundException());
		
		$response = $this->userService->getById(1);
		
		$this->assertEquals(404, $response->code);
		$this->assertFalse($response->success);
		$this->assertEquals("Utente non trovato", $response->jsonData["error"]);
	}
	
	public function testGetByIdDbException(): void {
		$mockRepo = $this->createMock(UserRepository::class);
		$mockRepo->method('getById')
		->with(1)
		->willThrowException(new Exception());
		
		$service = new DefaultUserService($mockRepo);
		
		$response = $service->getById(1);
		
		$this->assertEquals(500, $response->code);
		$this->assertFalse($response->success);
	}
	
	public function testUpdateSuccess(): void {
		$user = new User(1, "John Doe", "john@example.com", "hashed_password");
		$updatedUser = new User(1, "John Doe", "john@example.com", "hashed_password");
		
		$this->userRepository->expects($this->once())
		->method('update')
		->with($this->equalTo($user))
		->willReturn($updatedUser);
		
		$response = $this->userService->update($user);
		
		$this->assertEquals(200, $response->code);
		$this->assertTrue($response->success);
		$this->assertArrayHasKey("user", $response->jsonData);
		$this->assertEquals($updatedUser, $response->jsonData["user"]);
	}
	
	public function testUpdateUserNotFound(): void {
		$user = new User(999, "Non Existent", "nonexistent@example.com", "password");
		
		$this->userRepository->expects($this->once())
		->method('update')
		->with($this->equalTo($user))
		->willThrowException(new UserNotFoundException("Utente non trovato"));
		
		$response = $this->userService->update($user);
		
		$this->assertEquals(404, $response->code);
		$this->assertFalse($response->success);
		$this->assertArrayHasKey("error", $response->jsonData);
		$this->assertEquals("Utente non trovato", $response->jsonData["error"]);
	}
	
	public function testUpdateGenericException(): void {
		$user = new User(1, "John Doe", "john@example.com", "password");
		
		$this->userRepository->expects($this->once())
		->method('update')
		->with($this->equalTo($user))
		->willThrowException(new Exception("Errore generico del database"));
		
		$response = $this->userService->update($user);
		
		$this->assertEquals(500, $response->code);
		$this->assertFalse($response->success);
		$this->assertArrayHasKey("error", $response->jsonData);
		$this->assertEquals("Errore generico del database", $response->jsonData["error"]);
	}
	
	public function testUpdateWithDifferentUserData(): void {
		$originalUser = new User(1, "Old Name", "old@example.com", "password");
		$updatedUser = new User(1, "New Name", "new@example.com", "password");
		
		$this->userRepository->expects($this->once())
		->method('update')
		->with($this->callback(function($user) {
			return $user->id === 1 && 
			$user->name === "New Name" && 
			$user->email === "new@example.com";
		}))
		->willReturn($updatedUser);
		
		$response = $this->userService->update($updatedUser);
		
		$this->assertEquals(200, $response->code);
		$this->assertTrue($response->success);
		$this->assertEquals("New Name", $response->jsonData["user"]->name);
		$this->assertEquals("new@example.com", $response->jsonData["user"]->email);
	}
	
	public function testUpdateDatabaseException(): void {
		$user = new User(1, "John Doe", "john@example.com", "password");
		
		$this->userRepository->expects($this->once())
		->method('update')
		->with($this->equalTo($user))
		->willThrowException(new PDOException("Database connection failed"));
		
		$response = $this->userService->update($user);
		
		$this->assertEquals(500, $response->code);
		$this->assertFalse($response->success);
		$this->assertArrayHasKey("error", $response->jsonData);
	}
}