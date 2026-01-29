<?php

namespace booking;

use booking\commands\InsertFieldBookingCommand;
use booking\fields\FieldsBookingService;
use core\http\CommandController;
use core\di\Container;
use core\http\ControllerTypes;
use booking\fields\FieldBookingRepository;
use resources\PostgreFieldsRepository;

/**
 * Controller per le prenotazioni
 */
class BookingController extends CommandController {

	public function __construct(private FieldsBookingService $fieldService){
		parent::__construct();
	}
	
	protected function registerCommands(): void{
		$this->registry->register("field", new InsertFieldBookingCommand($this->fieldService));
	}
	
	/**
	 * Factory Method - Registra le dipendenze del modulo Booking
	 */
	public static function register(Container $container): void {
		// Registra FieldBookingRepository
		$container->register(FieldBookingRepository::class, function($c) {
			return new FieldBookingRepository($c->get('pdo'));
		}, true);

		// Registra FieldsBookingService
		$container->register(FieldsBookingService::class, function($c) {
			return new FieldsBookingService(
				$c->get(PostgreFieldsRepository::class),
				$c->get(FieldBookingRepository::class)
			);
		}, true);

		// Registra il controller
		$container->register(ControllerTypes::BOOKING->value, function($c) {
			return new self($c->get(FieldsBookingService::class));
		});
	}
}
