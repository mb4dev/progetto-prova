<?php 

namespace core\http;
use core\http\interfaces\ResponseStrategy;
use core\http\Response;

class HttpResponse implements ResponseStrategy {

	public function __construct() {
	}

    public function response(Response $response): void {
        header("Access-Control-Allow-Origin: http://localhost:8080");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header("Access-Control-Allow-Credentials: true");
        http_response_code($response->code);
        
        echo json_encode([
            "success" => $response->success,
            "data" => $response->jsonData
        ]);
    }
}
