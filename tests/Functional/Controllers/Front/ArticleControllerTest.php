<?php

namespace App\Tests\Functional\Controllers\Front;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArticleControllerTest extends WebTestCase
{
    public function testHomePageLoadsSuccessfully(): void
    {
        $client = static::createClient();

        $entityManager = self::getContainer()
            ->get(EntityManagerInterface::class);

        $article = new Article();

        $article->setTitle('My First Article');
        $article->setSubtitle('Test subtitle');
        $article->setSlug('my-first-article');
        $article->setContent('Test content');

        $entityManager->persist($article);
        $entityManager->flush();

        $url = self::getContainer()
            ->get('router')
            ->generate('front.articles.show', [
                'slug' => 'my-first-article',
            ]);

        $client->request('GET', $url);

        self::assertResponseIsSuccessful();
    }
}
