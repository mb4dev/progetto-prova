<?php

namespace features\payments\controller;

use core\interfaces\HttpSecurity;
use core\interfaces\Selector;
use core\utility\CommandController;
use features\payments\commands\PayCommand;

final class PaymentController extends CommandController {
	function __construct(HttpSecurity $authMiddleware, private Selector $paymentSelector) {
		parent::__construct($authMiddleware);
	}

	protected function registerCommands(): void{
		$this->registry->register("", new PayCommand($this->paymentSelector));
	}

}