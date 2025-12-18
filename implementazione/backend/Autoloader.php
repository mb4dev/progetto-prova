<?php

final class Autoloader {
	private array $directories;

	public function __construct(array $directories = []) {
		$this->directories = $directories;
	}

	public function addDirectory(string $path): void {
		$this->directories[] = $path;
	}

	public function register(): void {
		spl_autoload_register(function ($class) {
			$classPath = str_replace('\\', DIRECTORY_SEPARATOR, $class);
			
			foreach ($this->directories as $directory) {
				$file = $directory . DIRECTORY_SEPARATOR . $classPath . '.php';
				if (file_exists($file)) {
					//echo "File caricato: $file\n";
					require_once $file;
					return;
				}
			}

			$file = $classPath . '.php';
			if (file_exists($file)) {
					echo "File caricato: $file\n";

				require_once $file;
			}
		});
	}
}