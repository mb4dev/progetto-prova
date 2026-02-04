<?php

namespace core\exceptions;
use core\http\Response;
use Throwable;
use core\exceptions\CustomException;
use core\interfaces\ResponseStrategy;

class GlobalExceptionHandler {

	public function __construct(private ResponseStrategy $responseStrategy) {
	}
    private function handle(Throwable $error) : void {
		$this->logError($error);
		if($error instanceof CustomException) {
			$this->responseStrategy->response(new Response($error->getCode(), false, ["error" => $error->getMessage()]));
		}
		else{
			$this->responseStrategy->response(new Response(500, false, ["error" => $error->getMessage()]));
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