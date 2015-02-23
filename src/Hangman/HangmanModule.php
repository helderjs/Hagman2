<?php

namespace Hangman;

use Hangman\Controller\GameController;
use Silex\ControllerProviderInterface;
use Silex\Application;

class HangmanModule implements ControllerProviderInterface
{
    /**
     * @inheritdoc
     */
    public function connect(Application $app)
    {
        // Pega o gerenciador de controllers do silex
        $routing = $app['controllers_factory'];

        GameController::createRoutes($routing);

        return $routing;
    }
}
