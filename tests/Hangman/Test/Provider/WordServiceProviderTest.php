<?php

namespace Hangman\Provider;

use Silex\Application;

class WordServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testOptionsInitializer()
    {
        $app = new Application();
        $app['word.path'] = realpath(__DIR__ . "/../../../../var/words.english.txt");
        $app->register(new WordServiceProvider());

        $word = $app['word.new']();
        $this->assertNotEmpty($word);
        $this->assertTrue(is_string($word));
        $this->assertGreaterThanOrEqual(3, strlen($word));
    }
}
