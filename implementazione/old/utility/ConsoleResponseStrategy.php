<?php

final class ConsoleResponseStrategy implements ResponseStrategy {
	public function response(Response $response): void {
		print_r($response);
	}
}