	<?php
	
final class Response {
	public function __construct(
	public int $code,
	public bool $success,
	public array $jsonData
	) {}
}

