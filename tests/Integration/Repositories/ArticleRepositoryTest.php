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

        $articleOne = new Article();

        $articleOne->setTitle('Article One');
        $articleOne->setSubtitle('First article');
        $articleOne->setSlug('article-one');
        $articleOne->setContent('Article one content');

        $articleOne->setPublishedAt(
            new \DateTimeImmutable('2026-10-01')
        );

        $articleTwo = new Article();

        $articleTwo->setTitle('Article Two');
        $articleTwo->setSubtitle('Second article');
        $articleTwo->setSlug('article-two');
        $articleTwo->setContent('Article two content');

        $articleTwo->setPublishedAt(
            new \DateTimeImmutable('2026-10-02')
        );

        $articleThree = new Article();

        $articleThree->setTitle('Article Three');
        $articleThree->setSubtitle('Second article');
        $articleThree->setSlug('article-three');
        $articleThree->setContent('Article three content');

        $articleThree->setPublishedAt(
            new \DateTimeImmutable('2026-10-03')
        );

        $articleFour = new Article();

        $articleFour->setTitle('Article Four');
        $articleFour->setSubtitle('Second article');
        $articleFour->setSlug('article-four');
        $articleFour->setContent('Article four content');

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
