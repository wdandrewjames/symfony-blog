<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $article = new Article();

        $article->setTitle('Getting Started with Symfony');
        $article->setSubtitle('My first steps with Doctrine');
        $article->setSlug('getting-started-with-symfony');
        $article->setContent('Article content goes here...');
        $article->setPublishedAt(new \DateTimeImmutable());

        $manager->persist($article);

        $manager->flush();
    }
}
