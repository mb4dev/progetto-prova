<?php

namespace auth;

use auth\interfaces\AuthService;
use auth\commands\LoginCommand;
use auth\commands\RegisterCommand;
use auth\interfaces\AuthRepository;
use core\http\CommandController;
use core\di\Container;
use core\http\ControllerTypes;
use core\middleware\AuthMiddleware;
use core\utility\DefaultPasswordManager;
use core\utility\interfaces\JwtTokenService;
use core\utility\interfaces\PasswordManager;
use core\utility\jwt\MyJwtService;

/**
 * Controller per l'autenticazione
 * 
 * Usa il Factory Method pattern per auto-registrarsi nel container
 */

final class AuthController extends CommandController {

	public function __construct(private AuthService $service) {
		parent::__construct();
	}
	
	protected function registerCommands(): void{
		$this->registry->register("login", new LoginCommand($this->service));
		$this->registry->register("register", new RegisterCommand($this->service));
	}
	
	/**
	 * Factory Method - Registra tutte le dipendenze del modulo Auth
	 */
	public static function register(Container $container): void {
		// Registra PasswordManager
		$container->register(PasswordManager::class, function($c) {
			return new DefaultPasswordManager();
		}, true);

		// Registra JwtTokenService
		$container->register(JwtTokenService::class, function($c) {
			return new MyJwtService();
		}, true);

		// Registra AuthRepository
		$container->register(AuthRepository::class, function($c) {
			return new PostgreAuthRepository($c->get('pdo'));
		}, true);

		// Registra AuthService
		$container->register(AuthService::class, function($c) {
			return new DefaultAuthService(
				$c->get(AuthRepository::class),
				$c->get(PasswordManager::class),
				$c->get(JwtTokenService::class)
			);
		}, true);

		// Registra AuthMiddleware
		$container->register(AuthMiddleware::class, function($c) {
			return new AuthMiddleware(
				$c->get(AuthRepository::class),
				$c->get(JwtTokenService::class)
			);
		}, true);

		// Registra il controller
		$container->register(ControllerTypes::AUTH->value, function($c) {
			return new self($c->get(AuthService::class));
		});
	}
}