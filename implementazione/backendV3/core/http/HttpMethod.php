<?php

namespace core\http;

enum HttpMethod : string {
	case GET = "get";
	case POST = "post";
}