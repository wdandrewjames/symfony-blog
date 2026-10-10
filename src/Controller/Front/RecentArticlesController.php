<?php

namespace App\Controller\Front;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class RecentArticlesController extends AbstractController
{
    public function __invoke(ArticleRepository $articleRepository): Response
    {
        $recentArticles = $articleRepository->findLatestPublished(3);

        return $this->render('fragments/_recent_articles.html.twig', [
            'recent_articles' => $recentArticles,
        ]);
    }
}
