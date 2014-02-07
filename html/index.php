<?php
/**
 * Front controller for the project
 *
 * @codingstandard ftlabs-phpcs
 * @copyright The Financial Times Limited [All Rights Reserved]
 */

require_once __DIR__."/../app/global.php";

// Clean up the URI value provided by PHP to determine the controller to be used for this request
$uri = $_SERVER["REQUEST_URI"];
$uri = (strpos($uri, "?") !== false) ? substr($uri, 0, strpos($uri, "?")) : $uri;
$uri = ($uri) ? "/".trim($uri, "/") : "/";

// Set default uri
if (empty($uri) or ($uri == "/")) {
	$uri = "/hello";
}

switch ($uri) {
	case "/hello":
	case "/goodbye":
	case "/compliment":
		require PROJROOT."/app/controllers/".substr($uri, 1).".php";
		break;
	default:
		require PROJROOT."/app/controllers/404.php";
}
