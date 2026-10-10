<?php

namespace App\Tests\Functional\Controllers\Front;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TipsControllerTest extends WebTestCase
{
    public function testTipsPageLoadsSuccessfully(): void
    {
        $client = static::createClient();

        $url = self::getContainer()
            ->get('router')
            ->generate('front.tips');

        $client->request('GET', $url);

        self::assertResponseIsSuccessful();
    }
}
