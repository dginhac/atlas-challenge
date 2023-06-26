<?php

namespace App\Controller;

use App\Entity\Docker;
use App\Entity\Report;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        /** @var User $user */
        $dockers = $this->entityManager->getRepository(Docker::class)->findAll();
        $reports = $this->entityManager->getRepository(Report::class)->findAll();
        return $this->render('admin/admin.html.twig', [
            'title' => 'Admin zone',
            'dockers' => $dockers,
            'reports' => $reports,
        ]);
    }
}