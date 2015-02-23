<?php

namespace Hangman\Controller;

use Silex\Application;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GameController
{

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
        $newGame = [
            'id' => uniqid(),
            'word' => $app['word.new'](),
            'tries_left' => 6,
            'status' => 'busy',
            'wrong_chars' => [],
            'guessed_chars' => [],
        ];
        $app['session']->set($newGame['id'], $newGame);

        $history = $app['session']->get('history', []);
        $history[] = $newGame['id'];
        $app['session']->set('history', $history);

        return new JsonResponse(['id' => $newGame['id'], 'length' => strlen($newGame['word'])]);
    }

    /**
     * Get a overview of all games
     *
     * @param Application $app
     * @return JsonResponse
     */
    public function getGames(Application $app)
    {
        $history = $app['session']->get('history');
        $all = [];

        foreach ($history as $gameId) {
            $game = $app['session']->get($gameId);

            $all[] = [
                'id' => $gameId,
                'word' => $game['word'],
                'status' => $game['status'],
            ];
        }


        return new JsonResponse($all);
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
        $game = $app['session']->get($id);

        if (empty($game)) {
            return new JsonResponse(['message' => 'Game not found'], 404);
        }

        unset($game['word']);
        return new JsonResponse($game);
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
        $game = $app['session']->get($id);
        $result = ['guessed' => false];
        $char = $request->get('char', false);

        if ($game['tries_left'] == 0) {
            return new JsonResponse(['message' => 'You already have lost the game'], 406);
        }

        if ($char === false || empty($char)) {
            return new JsonResponse(['message' => 'None char sent'], 400);
        }

        if (strlen($char) > 1) {
            return new JsonResponse(['message' => 'Just one char must be sent'], 400);
        }

        if (in_array($char, $game['guessed_chars']) || in_array($char, $game['wrong_chars'])) {
            return new JsonResponse(['message' => 'Char was already tried'], 400);
        }

        if (strstr($game['word'], $char)) {
            $result['guessed'] = true;
            $result['positions'] = [];
            $game['guessed_chars'][] = $char;

            foreach (str_split($game['word']) as $key => $value) {
                if ($value == $char) {
                    $result['positions'][] = $key;
                }
            }
        } else {
            $game['wrong_chars'][] = $char;
            $game['tries_left']--;
        }

        $app['session']->set($id, $game);

        return new JsonResponse($result);
    }
}