<?php

namespace Hangman\Test\Controller;

use Silex\Application;
use Silex\WebTestCase;

class GameControllerTest extends WebTestCase
{
    public function createApplication()
    {
        $app = require __DIR__ . '/../../../../src/app.php';
        require __DIR__ . '/../../../../config/prod.php';
        $app['session.test'] = true;
        $app['debug'] = true;
        $app['exception_handler']->disable();

        return $app;
    }

    public function testStartGame()
    {
        $client = $this->createClient();
        $client->request('POST', '/games');

        $this->assertTrue($client->getResponse()->isOk());
        $result = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('length', $result);
        $this->assertGreaterThanOrEqual(3, $result['length']);
        $this->assertCount(1, $this->app['session']->get('history'));
    }

    public function testGetGames()
    {
        $game = [
            'id' => 123456,
            'word' => 'abababa',
            'tries_left' => 6,
            'status' => 'busy',
            'wrong_chars' => [],
            'guessed_chars' => [],
        ];
        $this->app['session']->set('123456', $game);

        $game['id'] = 654321;
        $game['word'] = 'hangman';
        $game['status'] = 'success';
        $this->app['session']->set('654321', $game);

        $game['id'] = 321456;
        $game['word'] = 'phpgame';
        $game['status'] = 'fail';
        $this->app['session']->set('321456', $game);
        $this->app['session']->set('history', [123456, 654321, 321456]);

        $client = $this->createClient();
        $client->request('GET', '/games');

        $this->assertTrue($client->getResponse()->isOk());
        $result = json_decode($client->getResponse()->getContent(), true);
        $this->assertCount(3, $result);
        foreach ($result as $game) {
            $this->assertArrayHasKey('id', $game);
            $this->assertArrayHasKey('word', $game);
            $this->assertArrayHasKey('status', $game);
        }
    }

    public function testGetGame()
    {
        // Testing get game
        $game = [
            'id' => 123456,
            'word' => 'abababa',
            'tries_left' => 6,
            'status' => 'busy',
            'wrong_chars' => [],
            'guessed_chars' => [],
        ];
        $this->app['session']->set('123456', $game);

        $client = $this->createClient();
        $client->request('GET', '/games/123456');

        $this->assertTrue($client->getResponse()->isOk());
        $result = json_decode($client->getResponse()->getContent(), true);
        unset($game['word']);
        $this->assertEquals($game, $result);

        $client = $this->createClient();
        $client->request('GET', '/games/12345');

        $this->assertTrue($client->getResponse()->isClientError());
        $result = json_decode($client->getResponse()->getContent(), true);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('message', $result);
        $this->assertEquals('Game not found', $result['message']);
    }

    public function testUpdateGame()
    {
        // Testing guessing chars
        $this->app['session']->set(
            '123456',
            [
                'id' => 123456,
                'word' => 'abababa',
                'tries_left' => 6,
                'status' => 'busy',
                'wrong_chars' => [],
                'guessed_chars' => [],
            ]
        );

        $client = $this->createClient();
        $client->request('POST', '/games/123456', ['char' => 'a']);

        $this->assertTrue($client->getResponse()->isOk());
        $result = json_decode($client->getResponse()->getContent(), true);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('guessed', $result);
        $this->assertArrayHasKey('positions', $result);
        $this->assertTrue($result['guessed']);
        $this->assertEquals([0, 2, 4, 6], $result['positions']);

        // Testing wrong chars
        $client = $this->createClient();
        $client->request('POST', '/games/123456', ['char' => 'c']);

        $this->assertTrue($client->getResponse()->isOk());
        $result = json_decode($client->getResponse()->getContent(), true);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('guessed', $result);
        $this->assertFalse($result['guessed']);

        // Testing sending a string
        $client = $this->createClient();
        $client->request('POST', '/games/123456', ['char' => 'ab']);

        $this->assertTrue($client->getResponse()->isClientError());
        $result = json_decode($client->getResponse()->getContent(), true);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('message', $result);
        $this->assertEquals('Just one char must be sent', $result['message']);

        // Testing sending repeated char
        $client = $this->createClient();
        $client->request('POST', '/games/123456', ['char' => 'a']);

        $this->assertTrue($client->getResponse()->isClientError());
        $result = json_decode($client->getResponse()->getContent(), true);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('message', $result);
        $this->assertEquals('Char was already tried', $result['message']);

        // Testing no sending char
        $client = $this->createClient();
        $client->request('POST', '/games/123456');

        $this->assertTrue($client->getResponse()->isClientError());
        $result = json_decode($client->getResponse()->getContent(), true);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('message', $result);
        $this->assertEquals('None char sent', $result['message']);

        // Testing lost game
        $game = $this->app['session']->get('123456');
        $game['tries_left'] = 0;
        $this->app['session']->set('123456', $game);

        $client = $this->createClient();
        $client->request('POST', '/games/123456', ['char' => 'b']);

        $this->assertTrue($client->getResponse()->isClientError());
        $result = json_decode($client->getResponse()->getContent(), true);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('message', $result);
        $this->assertEquals('You already have lost the game', $result['message']);
    }
}
