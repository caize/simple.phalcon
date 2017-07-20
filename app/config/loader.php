<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->modelsDir
    ]
)->register();


/**
 * 注册命名空间
 */
$loader->registerNamespaces([
    'Controllers\Api' => '../app/controllers/api',
    'Controllers\Admin' => '../app/controllers/admin',
    'Controllers\ApiPublic' => '../app/controllers/public',
    'Controllers' => '../app/controllers',
    'Library' => '../app/library',
    'Services' => '../app/services',
    'Models' => '../app/models',
])->register();

