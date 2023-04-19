<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_under_construction')]
    public function underConstruction(): Response
    {
        return $this->render('home/under-construction.html.twig', [
            'title' => 'Welcome to the atlas challenge'
        ]);
    }

    #[Route('/dataset', name: 'app_dataset')]
    public function dataset(): Response
    {
        return $this->render('home/dataset.html.twig', [
            'title' => 'Dataset'
        ]);
    }

    #[Route('/challenge', name: 'app_challenge')]
    public function challenge(): Response
    {
        return $this->render('home/challenge.html.twig', [
            'title' => 'Challenge'
        ]);
    }


    #[Route('/beta', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'title' => 'Welcome to the atlas challenge'
        ]);
    }
}
