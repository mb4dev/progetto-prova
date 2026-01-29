<?php

namespace core\di;

use Exception;

/**
 * Dependency Injection Container semplice
 * 
 * Questo container permette di:
 * - Registrare factory per creare oggetti
 * - Risolvere dipendenze automaticamente
 * - Gestire singleton (oggetti condivisi)
 * 
 * Esempio d'uso:
 * $container->register('pdo', function($c) {
 *     return new PDO(...);
 * }, true); // true = singleton
 * 
 * $pdo = $container->get('pdo');
 */
class Container {
    /** @var array Factory functions per creare gli oggetti */
    private array $bindings = [];
    
    /** @var array Istanze singleton già create */
    private array $instances = [];
    
    /** @var array Flag per indicare se un binding è singleton */
    private array $singletons = [];

    /**
     * Registra una factory per creare un oggetto
     * 
     * @param string $name Nome del servizio
     * @param callable $factory Funzione che crea l'oggetto
     * @param bool $singleton Se true, l'oggetto viene creato una sola volta
     */
    public function register(string $name, callable $factory, bool $singleton = false): void {
        $this->bindings[$name] = $factory;
        $this->singletons[$name] = $singleton;
    }

    /**
     * Registra un'istanza già creata (sempre singleton)
     * 
     * @param string $name Nome del servizio
     * @param mixed $instance Istanza dell'oggetto
     */
    public function instance(string $name, $instance): void {
        $this->instances[$name] = $instance;
        $this->singletons[$name] = true;
    }

    /**
     * Ottiene un'istanza del servizio richiesto
     * 
     * @param string $name Nome del servizio
     * @return mixed L'istanza del servizio
     * @throws Exception Se il servizio non è registrato
     */
    public function get(string $name) {
        // Se è un singleton già creato, restituiscilo
        if (isset($this->instances[$name])) {
            return $this->instances[$name];
        }

        // Se non è registrato, errore
        if (!isset($this->bindings[$name])) {
            throw new Exception("Servizio '$name' non registrato nel container");
        }

        // Crea l'istanza usando la factory
        $instance = $this->bindings[$name]($this);

        // Se è un singleton, salvalo per riutilizzarlo
        if ($this->singletons[$name]) {
            $this->instances[$name] = $instance;
        }

        return $instance;
    }

    /**
     * Verifica se un servizio è registrato
     * 
     * @param string $name Nome del servizio
     * @return bool True se il servizio è registrato
     */
    public function has(string $name): bool {
        return isset($this->bindings[$name]) || isset($this->instances[$name]);
    }
}
