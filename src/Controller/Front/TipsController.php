<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TipsController extends AbstractController
{
    #[Route('/tips', name: 'front.tips')]
    public function __invoke(): Response
    {
        return $this->render('tips.html.twig');
    }
}