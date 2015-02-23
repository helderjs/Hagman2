<?php

namespace Hangman\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;

class WordServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['word.new'] = $app->protect(function () use ($app) {
            $position = rand(0, 274907);

            $handle = @fopen($app['word.path'], "r");

            $line = 1;
            while (($buffer = fgets($handle)) !== false && $line < $position) {
                $line++;
            }

            fclose($handle);

            return $buffer;
        });
    }

    public function boot(Application $app)
    {}
}