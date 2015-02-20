<?php

namespace Hangman;

use Hangman\Controller\Game;
use Silex\ControllerProviderInterface;
use Silex\Application;

class Module implements ControllerProviderInterface
{
    /**
     * @inheritdoc
     */
    public function connect(Application $app)
    {
        // Pega o gerenciador de controllers do silex
        $routing = $app['controllers_factory'];

        Game::createRoutes($routing);

        return $routing;
    }
}
