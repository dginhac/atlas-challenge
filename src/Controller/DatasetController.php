<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DatasetController extends AbstractController
{
    #[Route('/dataset', name: 'app_dataset')]
    public function dataset(): Response
    {
        return $this->render('dataset/dataset.html.twig', [
            'title' => 'Dataset'
        ]);
    }

    #[Route('/dataset/download', name: 'app_dataset_download')]
    public function datasetDownload(EntityManagerInterface $entityManager): BinaryFileResponse
    {
        $user = $this->getUser();
        $user->setDataset(true);
        $entityManager->persist($user);
        $entityManager->flush();

        $dataset = 'downloads/atlas-train-dataset.zip';
        return $this->file($dataset);
    }
}