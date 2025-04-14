<?php

use Bootstrap\TelebotBootstrapper;
use Bootstrap\WebBootstrapper;
use GuzzleHttp\Exception\ConnectException;
use Psr\Container\ContainerInterface;

require_once 'vendor/autoload.php';

error_reporting(E_ERROR);

define('BASE_PATH', __DIR__);
Dotenv\Dotenv::createUnsafeImmutable(BASE_PATH)->safeLoad();
define('BASE_URL', getenv('BASE_URL'));



if (php_sapi_name() == 'cli') {
    $telebotBootstrapper = (new TelebotBootstrapper)->resetLocalCommands();

    while (true) {
        try {
            echo 'Listening for updates ...' . PHP_EOL;
            $telebotBootstrapper->runPolling();
        } catch (ConnectException $ex) {
            echo 'Connect error. Trying again ...' . PHP_EOL;
        }
    }
} else {
    if (str_starts_with($_SERVER['REQUEST_URI'], '/public')) {
        return false;
    }

    (new WebBootstrapper)->run();
}



/**
 * Returns the PSR-11 compliant container instance.
 *
 * @return ContainerInterface The application dependency container.
 */
function container()
{
    static $container = require_once 'container.php';
    return $container;
}
