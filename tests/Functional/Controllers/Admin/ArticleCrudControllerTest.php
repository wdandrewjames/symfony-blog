<?php

namespace App\Tests\Functional\Controllers\Admin;

use App\Controller\Admin\ArticleCrudController;
use App\Entity\Article;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArticleCrudControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $entityManager;
    private AdminUrlGeneratorInterface $adminUrlGenerator;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();

        $this->adminUrlGenerator = static::getContainer()
            ->get(AdminUrlGenerator::class);

        $this->entityManager = static::getContainer()
            ->get(EntityManagerInterface::class);

        $user = new User('admin@example.com');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword('unused-for-test');

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->client->loginUser($user);
    }

    public function testIndexPageLoadsSuccessfully(): void
    {
        $url = $this->adminUrlGenerator
            ->unsetAll()
            ->setController(ArticleCrudController::class)
            ->setAction(Action::INDEX)
            ->generateUrl();

        $this->client->request('GET', $url);

        self::assertResponseIsSuccessful();
    }

    public function testCreatePageLoadsSuccessfully(): void
    {
        $url = $this->adminUrlGenerator
            ->setController(ArticleCrudController::class)
            ->setAction(Action::NEW)
            ->generateUrl();

        $this->client->request('GET', $url);

        self::assertResponseIsSuccessful();
    }

    public function testEditPageLoadsSuccessfully(): void
    {
        $article = new Article();

        $article->setTitle('My First Article');
        $article->setSubtitle('Test subtitle');
        $article->setSlug('my-first-article');
        $article->setContent('Test content');

        $this->entityManager->persist($article);
        $this->entityManager->flush();

        $url = $this->adminUrlGenerator
            ->setController(ArticleCrudController::class)
            ->setAction(Action::EDIT)
            ->setEntityId($article->getId())
            ->generateUrl();

        $this->client->request('GET', $url);

        self::assertResponseIsSuccessful();
    }

    public function testCanCreateNewArticle(): void
    {
        $url = $this->adminUrlGenerator
            ->setController(ArticleCrudController::class)
            ->setAction(Action::NEW)
            ->generateUrl();

        $crawler = $this->client->request('GET', $url);

        $form = $crawler->selectButton('Create')->form([
            'Article[title]' => 'My First Article',
            'Article[subtitle]' => 'Test subtitle',
            'Article[slug]' => 'my-first-article',
            'Article[content]' => 'Test content',
        ]);

        $this->client->submit($form);

        $article = $this->entityManager
            ->getRepository(Article::class)
            ->findOneBy(['slug' => 'my-first-article']);

        self::assertResponseRedirects();
        self::assertNotNull($article);
        self::assertSame('My First Article', $article->getTitle());
    }

    public function testCanUpdateArticle(): void
    {
        $article = new Article();

        $article->setTitle('My First Article');
        $article->setSubtitle('Test subtitle');
        $article->setSlug('my-first-article');
        $article->setContent('Test content');

        $this->entityManager->persist($article);
        $this->entityManager->flush();

        $url = $this->adminUrlGenerator
            ->setController(ArticleCrudController::class)
            ->setAction(Action::EDIT)
            ->setEntityId($article->getId())
            ->generateUrl();

        $crawler = $this->client->request('GET', $url);

        $form = $crawler->selectButton('Save changes')->form([
            'Article[title]' => 'My First Article Updated',
            'Article[subtitle]' => 'Test subtitle',
            'Article[slug]' => 'my-first-article',
            'Article[content]' => 'Test content',
        ]);

        $this->client->submit($form);

        $updatedArticle = $this->entityManager
            ->getRepository(Article::class)
            ->find($article->getId());
        
        self::assertResponseRedirects();
        self::assertNotNull($article);
        self::assertSame('My First Article Updated', $updatedArticle->getTitle());
    }

    public function testCanDeleteArticle(): void
    {
        $article = new Article();
        $article->setTitle('Article to Delete');
        $article->setSubtitle('Test subtitle');
        $article->setSlug('article-to-delete');
        $article->setContent('Test content');

        $this->entityManager->persist($article);
        $this->entityManager->flush();

        $articleId = $article->getId();

        $indexUrl = $this->adminUrlGenerator
            ->setController(ArticleCrudController::class)
            ->setAction(Action::INDEX)
            ->generateUrl();

        $crawler = $this->client->request('GET', $indexUrl);

        self::assertResponseIsSuccessful();

        $form = $crawler
            ->filter('#action-confirmation-form')
            ->form();

        $deleteUrl = $this->adminUrlGenerator
            ->setController(ArticleCrudController::class)
            ->setAction(Action::DELETE)
            ->setEntityId($articleId)
            ->generateUrl();

        $this->client->request('POST', $deleteUrl, $form->getValues());

        self::assertResponseRedirects();

        $this->entityManager->clear();

        $deletedArticle = $this->entityManager
            ->getRepository(Article::class)
            ->find($articleId);

        self::assertResponseRedirects();

        self::assertNull($deletedArticle);
    }
}
