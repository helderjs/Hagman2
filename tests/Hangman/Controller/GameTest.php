<?php

namespace Hangman\Test\Controller;

use Silex\WebTestCase;

class GameTest extends WebTestCase
{
    public function createApplication()
    {
        $app = require __DIR__ . '/../../../src/app.php';
        $app['debug'] = true;
        $app['exception_handler']->disable();

        return $app;
    }

    public function testStartGame()
    {
    }

    public function testGetGames()
    {
    }

    public function testGtGame()
    {
    }

    public function testUpdateGame()
    {
    }
}
