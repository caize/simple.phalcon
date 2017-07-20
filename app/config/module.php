<?php

$baseName = '';

$application->registerModules([
    'admin' => 'Controllers\Admin',
    'api' => 'Controllers\Api',
    'public' => 'Controllers\ApiPublic',
]);
