<?php

final class DefaultURLParser implements URLParser {
	
	public function parse() : ParsedURL {
        $URL = $_SERVER["REQUEST_URI"] ?? throw new Exception("request uri not set");
        $path = parse_url($URL, PHP_URL_PATH);
        $path = str_replace("index.php", "", $path);

        $parts = array_values(array_filter(explode("/", $path), fn($p) => $p !== ""));

        $controller = $parts[0] ?? null;
        $action = $parts[1] ?? null;

        $queryParams = [];
        parse_str($_SERVER['QUERY_STRING'] ?? '', $queryParams);

        return new ParsedURL(
            $controller,
            $action,
            $queryParams
        );
	}
}