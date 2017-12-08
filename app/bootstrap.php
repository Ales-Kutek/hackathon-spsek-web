<?php

define("DS", DIRECTORY_SEPARATOR);

const PACKAGES_PATH = __DIR__ . DS . ".." . DS . "packages";

require __DIR__ . DS . '..' . DS . 'vendor' . DS . 'autoload.php';

// absolute filesystem path to the web root
define('WWW_DIR', realpath(dirname(__FILE__) . DS . ".." . DS . "www"));
// absolute filesystem path to the application root
define('APP_DIR', realpath(dirname(__FILE__)));

define('TEMP_DIR', realpath(dirname(__FILE__) . DS . ".." . DS . "temp"));

define('FILES_DIR', WWW_DIR . DS . "files");

$configurator = new Nette\Configurator;

$configurator->enableTracy(__DIR__ . DS . '..' . DS . 'log');

if (isset($_SERVER["REMOTE_ADDR"]) && $_SERVER["REMOTE_ADDR"] != "") {
    $configurator->setDebugMode($_SERVER["REMOTE_ADDR"]);
}

$configurator->setTimeZone('Europe/Prague');
$configurator->setTempDirectory(__DIR__ . DS . '..' . DS . 'temp');

$configurator->createRobotLoader()
    ->addDirectory(__DIR__)
    ->register();

$configurator->addConfig(__DIR__ . DS . 'config' . DS . 'config.neon');

\Tracy\Debugger::$maxDepth = 4;

//$configurator->addConfig(__DIR__ . '/config/config.local.neon');

$container = $configurator->createContainer();

return $container;
