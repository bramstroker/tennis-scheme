<?php
use Zend\Expressive\Application;
use Zend\ServiceManager\ServiceManager;

// Delegate static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server'
    && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))
) {
    return false;
}
chdir(dirname(__DIR__));
require 'vendor/autoload.php';

$container = new ServiceManager();
$container->setFactory()

/** @var Application $app */
$app = $container->get(Application::class);
$app->run();
