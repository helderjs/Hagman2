<?php

use Silex\Provider\MonologServiceProvider;
use Silex\Provider\WebProfilerServiceProvider;

require __DIR__ . '/prod.php';

$app['debug'] = true;

$app->register(new Silex\Provider\ServiceControllerServiceProvider());

$app->register(
    new MonologServiceProvider(),
    array(
        'monolog.logfile' => realpath(__DIR__ . '/../var/logs/silex_dev.log'),
    )
);
/*
$app->register(
    new WebProfilerServiceProvider(),
    array(
        'profiler.cache_dir' => __DIR__ . '/../var/cache/profiler',
    )
);*/
