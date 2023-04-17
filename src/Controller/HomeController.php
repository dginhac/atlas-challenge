<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/under_construction', name: 'under_construction')]
    public function underConstruction(): Response
    {
        return $this->render('home/under-construction.html.twig', [
            'title' => 'Welcome to the atlas challenge'
        ]);
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'title' => 'Welcome to the atlas challenge'
        ]);
    }
}
