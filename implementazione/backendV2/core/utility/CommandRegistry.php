<?php


namespace core\utility;

use core\utility\interfaces\Command;

class CommandRegistry{
    private array $commands = [];


    public function register(string $action, Command $command): void{
        $this->commands[strtolower($action)] = $command;
    }

    public function getCommand(string $action): ?Command{
        $action = strtolower($action);
        return $this->commands[$action] ?? null;
    }


    public function hasCommand(string $action): bool{
        return isset($this->commands[strtolower($action)]);
    }

    public function clear(): void{
        $this->commands = [];
    }
}