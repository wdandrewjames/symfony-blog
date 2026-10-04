<?php

namespace App\Tests\Controllers\Front;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostControllerTest extends WebTestCase
{
    public function testHomePageLoadsSuccessfully(): void
    {
        $client = static::createClient();

        $url = self::getContainer()
            ->get('router')
            ->generate('front.posts.show');

        $client->request('GET', '/post');

        self::assertResponseIsSuccessful();
    }
}
