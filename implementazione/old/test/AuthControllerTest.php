<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . "/bootstrap.php";

class AuthControllerTest extends TestCase {

    private AuthService $authService;
    private AuthController $controller;

    protected function setUp(): void {
        $this->authService = $this->createMock(AuthService::class);
        $this->controller = $this->getMockBuilder(AuthController::class)
            ->setConstructorArgs([$this->authService])
            ->onlyMethods(['getBody'])
            ->getMock();
    }

    protected function tearDown(): void {
        unset($_SERVER['REQUEST_METHOD']);
        unset($_SERVER['CONTENT_TYPE']);
        $_POST = [];
    }

    private function setServerMethod(string $method): void {
        $_SERVER['REQUEST_METHOD'] = $method;
    }

    public function testLoginSuccess(): void {
        $this->setServerMethod("POST");

        $body = ["email" => "test@example.com", "password" => "password"];
        
        $this->controller->method('getBody')->willReturn($body);

        $expectedResponse = new Response(200, true, [
            "message" => "Login effettuato", 
            "token" => "mock_token"
        ]);

        $this->authService->expects($this->once())
            ->method('login')
            ->with('test@example.com', 'password')
            ->willReturn($expectedResponse);

        $response = $this->controller->resolveAction("login");

        $this->assertEquals(200, $response->code);
        $this->assertTrue($response->success);
        $this->assertEquals("Login effettuato", $response->jsonData["message"]);
        $this->assertEquals("mock_token", $response->jsonData["token"]);
    }

    public function testLoginMissingEmail(): void {
        $this->setServerMethod("POST");

        $this->controller->method('getBody')->willReturn(["password" => "password"]);

        $response = $this->controller->resolveAction("login");

        $this->assertEquals(400, $response->code);
        $this->assertFalse($response->success);
        $this->assertEquals("Parametri non validi", $response->jsonData["error"]);
    }

    public function testLoginMissingPassword(): void {
        $this->setServerMethod("POST");

        $this->controller->method('getBody')->willReturn(["email" => "test@example.com"]);

        $response = $this->controller->resolveAction("login");

        $this->assertEquals(400, $response->code);
        $this->assertFalse($response->success);
        $this->assertEquals("Parametri non validi", $response->jsonData["error"]);
    }

    public function testLoginEmptyEmail(): void {
        $this->setServerMethod("POST");

        $this->controller->method('getBody')->willReturn([
            "email" => "", 
            "password" => "password"
        ]);

        $response = $this->controller->resolveAction("login");

        $this->assertEquals(400, $response->code);
        $this->assertFalse($response->success);
        $this->assertEquals("Parametri non validi", $response->jsonData["error"]);
    }

    public function testLoginEmptyPassword(): void {
        $this->setServerMethod("POST");

        $this->controller->method('getBody')->willReturn([
            "email" => "test@example.com", 
            "password" => ""
        ]);

        $response = $this->controller->resolveAction("login");

        $this->assertEquals(400, $response->code);
        $this->assertFalse($response->success);
        $this->assertEquals("Parametri non validi", $response->jsonData["error"]);
    }

    public function testLoginWrongMethod(): void {
        $this->setServerMethod("GET");

        $this->controller->method('getBody')->willReturn([
            "email" => "test@example.com", 
            "password" => "password"
        ]);

        $response = $this->controller->resolveAction("login");

        $this->assertEquals(405, $response->code);
        $this->assertFalse($response->success);
        $this->assertEquals("Metodo non consentito", $response->jsonData["error"]);
    }

    public function testRegisterSuccess(): void {
        $this->setServerMethod("POST");

        $body = [
            "name" => "Test User",
            "email" => "test@example.com", 
            "password" => "password"
        ];
        
        $this->controller->method('getBody')->willReturn($body);

        $expectedResponse = new Response(200, true, [
            "message" => "Registrazione effettuata", 
            "token" => "mock_token"
        ]);

        $this->authService->expects($this->once())
            ->method('register')
            ->with('Test User', 'test@example.com', 'password')
            ->willReturn($expectedResponse);

        $response = $this->controller->resolveAction("register");

        $this->assertEquals(200, $response->code);
        $this->assertTrue($response->success);
        $this->assertEquals("Registrazione effettuata", $response->jsonData["message"]);
        $this->assertEquals("mock_token", $response->jsonData["token"]);
    }

    public function testRegisterMissingName(): void {
        $this->setServerMethod("POST");

        $this->controller->method('getBody')->willReturn([
            "email" => "test@example.com", 
            "password" => "password"
        ]);

        $response = $this->controller->resolveAction("register");

        $this->assertEquals(400, $response->code);
        $this->assertFalse($response->success);
        $this->assertEquals("Parametri non validi", $response->jsonData["error"]);
    }

    public function testRegisterMissingEmail(): void {
        $this->setServerMethod("POST");

        $this->controller->method('getBody')->willReturn([
            "name" => "Test User",
            "password" => "password"
        ]);

        $response = $this->controller->resolveAction("register");

        $this->assertEquals(400, $response->code);
        $this->assertFalse($response->success);
        $this->assertEquals("Parametri non validi", $response->jsonData["error"]);
    }

    public function testRegisterMissingPassword(): void {
        $this->setServerMethod("POST");

        $this->controller->method('getBody')->willReturn([
            "name" => "Test User",
            "email" => "test@example.com"
        ]);

        $response = $this->controller->resolveAction("register");

        $this->assertEquals(400, $response->code);
        $this->assertFalse($response->success);
        $this->assertEquals("Parametri non validi", $response->jsonData["error"]);
    }

    public function testRegisterEmptyName(): void {
        $this->setServerMethod("POST");

        $this->controller->method('getBody')->willReturn([
            "name" => "",
            "email" => "test@example.com", 
            "password" => "password"
        ]);

        $response = $this->controller->resolveAction("register");

        $this->assertEquals(400, $response->code);
        $this->assertFalse($response->success);
        $this->assertEquals("Parametri non validi", $response->jsonData["error"]);
    }

    public function testRegisterEmptyEmail(): void {
        $this->setServerMethod("POST");

        $this->controller->method('getBody')->willReturn([
            "name" => "Test User",
            "email" => "", 
            "password" => "password"
        ]);

        $response = $this->controller->resolveAction("register");

        $this->assertEquals(400, $response->code);
        $this->assertFalse($response->success);
        $this->assertEquals("Parametri non validi", $response->jsonData["error"]);
    }

    public function testRegisterEmptyPassword(): void {
        $this->setServerMethod("POST");

        $this->controller->method('getBody')->willReturn([
            "name" => "Test User",
            "email" => "test@example.com", 
            "password" => ""
        ]);

        $response = $this->controller->resolveAction("register");

        $this->assertEquals(400, $response->code);
        $this->assertFalse($response->success);
        $this->assertEquals("Parametri non validi", $response->jsonData["error"]);
    }

    public function testRegisterWrongMethod(): void {
        $this->setServerMethod("PUT");

        $this->controller->method('getBody')->willReturn([
            "name" => "Test User",
            "email" => "test@example.com", 
            "password" => "password"
        ]);

        $response = $this->controller->resolveAction("register");

        $this->assertEquals(405, $response->code);
        $this->assertFalse($response->success);
        $this->assertEquals("Metodo non consentito", $response->jsonData["error"]);
    }

    public function testResolveActionNotFound(): void {
        $this->setServerMethod("POST");

        $this->controller->method('getBody')->willReturn([]);

        $response = $this->controller->resolveAction("nonexistent");

        $this->assertEquals(404, $response->code);
        $this->assertFalse($response->success);
        $this->assertEquals("Action non trovata", $response->jsonData["error"]);
    }

    public function testResolveActionCaseInsensitive(): void {
        $this->setServerMethod("POST");

        $body = ["email" => "test@example.com", "password" => "password"];
        
        $this->controller->method('getBody')->willReturn($body);

        $expectedResponse = new Response(200, true, [
            "message" => "Login effettuato", 
            "token" => "mock_token"
        ]);

        $this->authService->expects($this->once())
            ->method('login')
            ->with('test@example.com', 'password')
            ->willReturn($expectedResponse);

        $response = $this->controller->resolveAction("LOGIN");

        $this->assertEquals(200, $response->code);
        $this->assertTrue($response->success);
    }

    public function testResolveActionMixedCase(): void {
        $this->setServerMethod("POST");

        $body = [
            "name" => "Test User",
            "email" => "test@example.com", 
            "password" => "password"
        ];
        
        $this->controller->method('getBody')->willReturn($body);

        $expectedResponse = new Response(200, true, [
            "message" => "Registrazione effettuata", 
            "token" => "mock_token"
        ]);

        $this->authService->expects($this->once())
            ->method('register')
            ->willReturn($expectedResponse);

        // Test con "ReGiStEr" mixed case
        $response = $this->controller->resolveAction("ReGiStEr");

        $this->assertEquals(200, $response->code);
        $this->assertTrue($response->success);
    }
}