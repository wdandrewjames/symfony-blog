<?php

namespace App\DataFixtures;

use App\Entity\Article;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $article = new Article(
            title: 'Getting Started with Symfony',
            subtitle: 'My first steps with Doctrine',
            slug: 'getting-started-with-symfony',
            content: 'Article content goes here...',
        );

        $article->setPublishedAt(new DateTimeImmutable());

        $manager->persist($article);

        $manager->flush();
    }
}
