<?php

namespace App\Controller;

use App\Entity\Submission;
use App\Entity\User;
use App\Form\SubmissionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class ChallengeController extends AbstractController
{
    public const MAX_NUMBER_OF_SUBMISSIONS = 2;
    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/challenge', name: 'app_challenge')]
    public function challenge(Request $request, MailerInterface $mailer): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $form = null;
        $submissions_nb = 0;
        if ($user) {




            $submission = new Submission();
            $submission->setUser($user);
            $form = $this->createForm(SubmissionType::class, $submission, [
                'required_file' => true
            ]);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->entityManager->persist($submission);
                $this->entityManager->flush();
                //return $this->redirectToRoute('app_challenge');
            }
        }
        return $this->render('challenge/challenge.html.twig', [
            'title' => 'Challenge',
            'form' => $form,
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