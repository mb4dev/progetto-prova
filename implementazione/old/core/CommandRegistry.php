<?php

namespace core;

use core\interfaces\Command;

class CommandRegistry
{
    private array $commands = [];

    /**
     * Register a command for a given action name
     * 
     * @param string $action The action name (lowercase)
     * @param Command $command The command instance
     * @return void
     */
    public function register(string $action, Command $command): void
    {
        $this->commands[strtolower($action)] = $command;
    }

    /**
     * Get a command by action name
     * 
     * @param string $action The action name
     * @return Command|null The command instance or null if not found
     */
    public function getCommand(string $action): ?Command
    {
        $action = strtolower($action);
        return $this->commands[$action] ?? null;
    }

    /**
     * Check if a command is registered for the given action
     * 
     * @param string $action The action name
     * @return bool True if command exists, false otherwise
     */
    public function hasCommand(string $action): bool
    {
        return isset($this->commands[strtolower($action)]);
    }

    /**
     * Get all registered action names
     * 
     * @return array Array of registered action names
     */
    public function getRegisteredActions(): array
    {
        return array_keys($this->commands);
    }

    /**
     * Get all registered commands
     * 
     * @return array Associative array of action => command
     */
    public function getAllCommands(): array
    {
        return $this->commands;
    }

    /**
     * Remove a command by action name
     * 
     * @param string $action The action name
     * @return bool True if command was removed, false if not found
     */
    public function removeCommand(string $action): bool
    {
        $action = strtolower($action);
        if (isset($this->commands[$action])) {
            unset($this->commands[$action]);
            return true;
        }
        return false;
    }

    /**
     * Clear all registered commands
     * 
     * @return void
     */
    public function clear(): void
    {
        $this->commands = [];
    }

    /**
     * Get the total number of registered commands
     * 
     * @return int Number of registered commands
     */
    public function count(): int
    {
        return count($this->commands);
    }
}