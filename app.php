<?php

use Bootstrap\TelebotBootstrapper;
use Psr\Container\ContainerInterface;

require_once 'vendor/autoload.php';

define('BASE_PATH', __DIR__);
Dotenv\Dotenv::createUnsafeImmutable(BASE_PATH)->safeLoad();

(new TelebotBootstrapper)->resetLocalCommands()->runPolling();



/**
 * Returns the PSR-11 compliant container instance.
 *
 * @return ContainerInterface The application dependency container.
 */
function container() {
    static $container = require_once 'container.php';
    return $container;
}