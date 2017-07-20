<?php

use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Flash\Direct as Flash;
use Phalcon\Cache\Frontend\Data as FrontendData;
use Phalcon\Cache\Backend\Redis;

/**
 * 引入数据库配置信息
 * 根据环境变量获取对应的数据库配置
 */
$di->setShared('database_config', function () {
    if(ENVIRONMENT == 'development'){
        return include APP_PATH . "/config/development/database.php";
    }else if(ENVIRONMENT == 'testing'){
        return include APP_PATH . "/config/testing/database.php";
    }else{
        return include APP_PATH . "/config/database.php";
    }
});

/**
 * 注册全局公用配置
 * 根据环境变量获取对应配置信息
 */
$di->setShared('commonConfig', function () {
    if(ENVIRONMENT == 'development'){
        return include APP_PATH . "/config/development/common.php";
    }else{
        return include APP_PATH . "/config/common.php";
    }
});

/**
 * 注册支付回调逻辑方法
 * 用户支付处理的逻辑
 */
$di->setShared('pay_service', function () {
    return new \Services\PayService();
});

/**
 * 开启redis缓存服务
 */
$di->set('redis_cache', function () {
    $frontCache = new FrontendData(['lifetime' => 86400,]);
    $redisConfig = \Phalcon\Di::getDefault()->get('database_config');
    $cache = new Redis($frontCache, [
        "host" => $redisConfig['redis']['host'],
        "port" => $redisConfig['redis']['port'],
        "auth" => $redisConfig['redis']['auth'],
        "persistent" => false,
        "index" => 0,
    ]);
    return $cache;
});

/**
 * Shared configuration service
 */
$di->setShared('config', function () {
    return include APP_PATH . "/config/config.php";
});


/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->setShared('url', function () {
    $config = $this->getConfig();

    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
});

/**
 * Setting up the view component
 */
$di->setShared('view', function () {
    $config = $this->getConfig();

    $view = new View();
    $view->setDI($this);
    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines([
        '.volt' => function ($view) {
            $config = $this->getConfig();

            $volt = new VoltEngine($view, $this);

            $volt->setOptions([
                'compiledPath' => $config->application->cacheDir,
                'compiledSeparator' => '_'
            ]);

            return $volt;
        },
        '.phtml' => PhpEngine::class

    ]);

    return $view;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () {
    $config = $this->getConfig();

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    $params = [
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname,
        'port'=>$config->database->port,
        'charset' => $config->database->charset
    ];

    if ($config->database->adapter == 'Postgresql') {
        unset($params['charset']);
    }

    $connection = new $class($params);

    return $connection;
});


/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->setShared('modelsMetadata', function () {
    return new MetaDataAdapter();
});

/**
 * Register the session flash service with the Twitter Bootstrap classes
 */
$di->set('flash', function () {
    return new Flash([
        'error' => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice' => 'alert alert-info',
        'warning' => 'alert alert-warning'
    ]);
});

/**
 * Start the session the first time some component request the session service
 */
$di->setShared('session', function () {
    $session = new SessionAdapter();
    $session->start();
    return $session;
});