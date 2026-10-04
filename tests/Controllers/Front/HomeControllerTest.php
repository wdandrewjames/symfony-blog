<?php

namespace App\Tests\Controllers\Front;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function test_home_page_loads_successfully(): void
    { 
        $client = static::createClient();

        $url = self::getContainer()
            ->get('router')
            ->generate('home');

        $client->request('GET', $url);

        self::assertResponseIsSuccessful();
    }
}