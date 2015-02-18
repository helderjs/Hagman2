<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider());

$app->get('/', function () use ($app) {
    return $app['twig']->render('index.twig');
});

return $app;