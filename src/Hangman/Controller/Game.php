<?php

namespace Hangman\Controller;

use Silex\Application;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class Game {

    /**
     * Router manager
     *
     * @param ControllerCollection $routing
     * @return mixed
     */
    static public function createRoutes(ControllerCollection $routing)
    {
        $routing->post('/games', [new self(), 'startGame']);
        $routing->get('/games', [new self(), 'getGames']);
        $routing->get('/games/{id}', [new self(), 'getGame']);
        $routing->post('/games/{id}', [new self(), 'updateGame']);
    }

    /**
     * Create a new game
     *
     * @param Application $app
     * @return JsonResponse
     */
    public function startGame(Application $app)
    {
        return new JsonResponse();
    }

    /**
     * Get a overview of all games
     *
     * @param Application $app
     * @return JsonResponse
     */
    public function getGames(Application $app)
    {
        return new JsonResponse();
    }

    /**
     * Get a complete status of a game
     *
     * @param Application $app
     * @param $id
     * @return JsonResponse
     */
    public function getGame(Application $app, $id)
    {
        return new JsonResponse();
    }

    /**
     * Perform a gaming move
     *
     * @param Application $app
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function updateGame(Application $app, Request $request, $id)
    {
        return new JsonResponse();
    }
}