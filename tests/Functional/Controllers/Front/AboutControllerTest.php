<?php

namespace App\Tests\Functional\Controllers\Front;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AboutControllerTest extends WebTestCase
{
    public function testAboutPageLoadsSuccessfully(): void
    {
        $client = static::createClient();

        $url = self::getContainer()
            ->get('router')
            ->generate('front.about');

        $client->request('GET', $url);

        self::assertResponseIsSuccessful();
    }
}
