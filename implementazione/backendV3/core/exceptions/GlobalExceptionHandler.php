<?php

namespace core\exceptions;
use Throwable;
use core\exceptions\CustomException;

class GlobalExceptionHandler {
    private function handle(Throwable $error) : void {
		/*
		header("Access-Control-Allow-Origin: http://localhost:8080");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header("Access-Control-Allow-Credentials: true");
		*/
		header('Content-Type: application/json; charset=utf-8');
    
		$this->logError($error);
		if($error instanceof CustomException) {
			http_response_code($error->getCode());
			echo json_encode([
				"code" => $error->getCode(),
				"success" => false,
				"data" => ["error" => $error->getMessage()]
			]);
		}
		else{
			http_response_code(500);
			echo json_encode([
				"code" => 500,
				"success" => false,
				"data" => ["error" => $error->getMessage()]
			]);
		} 
    }

    private function logError(Throwable $error): void {
        $message = sprintf(
            "%s: %s in %s:%d\nStack trace:\n%s",
            get_class($error),
            $error->getMessage(),
            $error->getFile(),
            $error->getLine(),
            $error->getTraceAsString()
        );
        
        error_log($message);
    }


    public function register(){
        set_exception_handler(fn($ex) => $this->handle($ex));
    }
}