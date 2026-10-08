<?php

namespace App\Tests\Functional\Controllers\Front;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostControllerTest extends WebTestCase
{
    public function testHomePageLoadsSuccessfully(): void
    {
        $client = static::createClient();

        $entityManager = self::getContainer()
            ->get(EntityManagerInterface::class);

        $article = new Article(
            title: 'My First Article',
            subtitle: 'Test subtitle',
            slug: 'my-first-article',
            content: 'Test content',
        );

        $entityManager->persist($article);
        $entityManager->flush();

        $url = self::getContainer()
            ->get('router')
            ->generate('front.posts.show', [
                'slug' => 'my-first-article',
            ]);

        $client->request('GET', $url);

        self::assertResponseIsSuccessful();
    }
}
