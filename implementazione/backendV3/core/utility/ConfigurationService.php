<?php

namespace core\utility;

use core\exceptions\CustomException;
final class ConfigurationService {

	private array $config = [];

	public function __construct(string $configPath  = __DIR__ . "/../config.php")	{
		if(!file_exists($configPath)) throw new CustomException("file di configurazione inesistente", 500);
		$data = require($configPath);
		if(!is_array($data)) throw new CustomException("Il file di configurazione deve restituire un array", 500);
		$this->config = $data;
	}

	public function get(string $key, $default = null){
		$keys = explode(".", $key);

		$value = $this->config;
		foreach($keys as $k){
			if(!isset($value[$k])) return $default;

			$value = $value[$k];
		}

		return $value;
	}

}