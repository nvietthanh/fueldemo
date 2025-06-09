<?php

/**
 * Fuel is a fast, lightweight, community driven PHP 5.4+ framework.
 *
 * @package    Fuel
 * @version    1.8.2
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2019 Fuel Development Team
 * @link       https://fuelphp.com
 */

// Bootstrap the framework - THIS LINE NEEDS TO BE FIRST!
require COREPATH . 'bootstrap.php';

require_once APPPATH . 'classes/helpers/helper.php';

// Add framework overload classes here
\Autoloader::add_classes(array(
	// Example: 'View' => APPPATH.'classes/myview.php',
));

// Register the autoloader
\Autoloader::register();

/**
 * Your environment.  Can be set to any of the following:
 *
 * Fuel::DEVELOPMENT
 * Fuel::TEST
 * Fuel::STAGING
 * Fuel::PRODUCTION
 */
Fuel::$env = Arr::get($_SERVER, 'FUEL_ENV', Arr::get($_ENV, 'FUEL_ENV', getenv('FUEL_ENV') ?: Fuel::DEVELOPMENT));

// Initialize the framework with the config file.
\Fuel::init('config.php');

// Loading env
$env_file = realpath(__DIR__ . '/../.env');
if ($env_file && file_exists($env_file)) {
    $lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0 || trim($line) === '') {
            continue;
        }

        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value, " \t\n\r\0\x0B\"'");

            putenv("$name=$value");
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

set_exception_handler(function ($e) {
	$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
	$isJson = isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false;

	if ($isAjax || $isJson) {
		if ($e instanceof \Exception\ValidationException || $e instanceof \Exception\NotFoundException) {
			$response = $e->toJson();
			$status = $e->getStatus();
		} else {
			$status = 500;
			$response = [
				'error' => get_class($e),
				'message' => $e->getMessage(),
				'file' => $e->getFile(),
				'line' => $e->getLine(),
				'trace' => explode("\n", $e->getTraceAsString())
			];
		}

		header('Content-Type: application/json');
		http_response_code($status);
		echo json_encode($response);

		exit;
	}

	return \Errorhandler::exception_handler($e);
});
