<?php

namespace Hangman\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;

class WordServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['word.new'] = $app->protect(function () use ($app) {});
    }

    public function boot(Application $app)
    {}
}