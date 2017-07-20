<?php

$router = $di->getRouter();

foreach ($application->getModules() as $key => $name) {
    $router->add('/' . $key . '/:controller/:action/:params', [
        'namespace' => $name,
        'controller' => 1,
        'action' => 2,
        'params' => 3
    ]);
}
$router->notFound([
    'controller' => 'base',
    'action' => 'route404'
]);

//$router->handle();
