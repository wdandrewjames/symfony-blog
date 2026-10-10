<?php

namespace App\Tests\Functional\Controllers\Front;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactControllerTest extends WebTestCase
{
    public function testContactPageLoadsSuccessfully(): void
    {
        $client = static::createClient();

        $url = self::getContainer()
            ->get('router')
            ->generate('front.contact');

        $client->request('GET', $url);

        self::assertResponseIsSuccessful();
    }
}
