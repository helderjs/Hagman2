<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider());

$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    $lexer = new Twig_Lexer($twig, array(
        'tag_comment'   => array('{#', '#}'),
        'tag_block'     => array('{%', '%}'),
        'tag_variable'  => array('${', '}'),
        'interpolation' => array('#{', '}'),
    ));
    $twig->setLexer($lexer);

    return $twig;
}));

$app->mount('/', new \Hangman\Module());

$app->get('/', function () use ($app) {
    return $app['twig']->render('index.twig');
});

$app->get('/tmp', function () use ($app) {
    return new \Symfony\Component\HttpFoundation\JsonResponse();
});

$app->post('/tmp', function () use ($app) {
    return new \Symfony\Component\HttpFoundation\JsonResponse();
});

return $app;