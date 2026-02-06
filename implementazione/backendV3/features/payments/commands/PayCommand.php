<?php

namespace features\payments\commands;

use core\exceptions\CustomException;
use core\http\HttpMethod;
use core\http\Response;
use core\interfaces\Command;
use core\interfaces\Selector;
use core\model\Role;
use core\model\User;

final class PayCommand extends Command {
    public function __construct(
        private Selector $paymentTypeSelector
    ) {
        parent::__construct();
    }

    public function execute(array $params, array $query = [], ?User $user = null): Response {
		$userId = $user->id ?? throw new CustomException("Utente richiesto per questa operazione", 400);
        $strategy = $this->paymentTypeSelector->select($params["metodo_pagamento"]);

        $strategy->pay($userId, $params["items"]);
        //$strategy->execute([]);
		
        return new Response(200, true, []);
    }
    

    public function getRequiredBodyParameters(): array
    {
        return ["metodo_pagamento", "dati_pagamento", "items"];
    }
    public function getRequiredHttpMethod(): string {
        return HttpMethod::POST->value;
    }

    public function getRequiredRoles(): array {
        return [Role::USER->value, Role::ADMIN->value];
    }
}
