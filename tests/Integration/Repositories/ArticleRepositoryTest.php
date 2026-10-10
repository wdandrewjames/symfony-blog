<?php

namespace App\Tests\Integration\Repositories;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class ArticleRepositoryTest extends KernelTestCase
{
    public function testItFindsLatestPublishedArticles(): void
    {
        self::bootKernel();

        $entityManager = self::getContainer()
            ->get(EntityManagerInterface::class);

        $repository = self::getContainer()
            ->get(ArticleRepository::class);

        $articleOne = new Article(
            title: 'Article One',
            subtitle: 'First article',
            slug: 'article-one',
            content: 'Article one content',
        );

        $articleOne->setPublishedAt(
            new \DateTimeImmutable('2026-10-01')
        );

        $articleTwo = new Article(
            title: 'Article Two',
            subtitle: 'Second article',
            slug: 'article-two',
            content: 'Article two content',
        );

        $articleTwo->setPublishedAt(
            new \DateTimeImmutable('2026-10-02')
        );

        $articleThree = new Article(
            title: 'Article Three',
            subtitle: 'Third article',
            slug: 'article-three',
            content: 'Article three content',
        );

        $articleThree->setPublishedAt(
            new \DateTimeImmutable('2026-10-03')
        );

        $articleFour = new Article(
            title: 'Article Four',
            subtitle: 'forth article',
            slug: 'article-four',
            content: 'Article four content',
        );

        $articleFour->setPublishedAt(
            new \DateTimeImmutable('2026-10-04')
        );

        $entityManager->persist($articleOne);
        $entityManager->persist($articleTwo);
        $entityManager->persist($articleThree);
        $entityManager->persist($articleFour);
        $entityManager->flush();

        $articles = $repository->findLatestPublished(3);

        self::assertCount(3, $articles);

        self::assertSame('Article Four', $articles[0]->getTitle());
        self::assertSame('Article Three', $articles[1]->getTitle());
        self::assertSame('Article Two', $articles[2]->getTitle());
    }
}
