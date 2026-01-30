<?php

namespace core\utility;

use core\interfaces\URLParser;

final class StandardURLParser implements URLParser {

    public function parse(string $requestUri): ParsedURL{
        $path = parse_url($requestUri, PHP_URL_PATH);
        $path = str_replace("index.php", "", $path);

        $parts = array_values(array_filter(explode("/", $path), fn($p) => $p !== ""));

        $controller = $parts[0] ?? null;
        $action = $parts[1] ?? "";

        return new ParsedURL(
            $controller,
            $action,
        );
	}
}