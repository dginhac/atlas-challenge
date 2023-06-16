<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChallengeController extends AbstractController
{
    #[Route('/challenge', name: 'app_challenge')]
    public function challenge(): Response
    {
        return $this->render('challenge/challenge.html.twig', [
            'title' => 'Challenge'
        ]);
    }

    #[Route('/docker/download', name: 'app_docker_download')]
    public function datasetDownload(EntityManagerInterface $entityManager): BinaryFileResponse
    {
        $user = $this->getUser();
        $user->setDocker(true);
        $entityManager->persist($user);
        $entityManager->flush();

        $dataset = 'downloads/atlas-docker.zip';
        return $this->file($dataset);
    }
}
