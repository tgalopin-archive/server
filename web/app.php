<?php

$env = getenv('SYMFONY_ENV');
$env = in_array($env, [ 'dev', 'test', 'preprod', 'prod' ], true) ? $env : 'prod';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\ClassLoader\ApcClassLoader;
use Symfony\Component\Debug\Debug;

$loader = require_once __DIR__.'/../app/bootstrap.php.cache';

if ($env === 'prod') {
    $apcLoader = new ApcClassLoader(sha1(__FILE__), $loader);
    $loader->unregister();
    $apcLoader->register(true);
}

if (in_array($env, [ 'dev', 'test' ], true)) {
    Debug::enable();
}

require_once __DIR__.'/../app/AppKernel.php';

$kernel = new AppKernel($env, $env === 'dev');
$kernel->loadClassCache();

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();

$kernel->terminate($request, $response);
