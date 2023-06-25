<?php

namespace App\Controller;

use App\Entity\Submission;
use App\Entity\Docker;
use App\Entity\Report;
use App\Entity\User;
use App\Form\SubmissionType;
use App\Form\DockerType;
use App\Form\ReportType;
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
    public function challenge(Request $request,  MailerInterface $mailer):
    Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $dockerForm = null;
        $reportForm = null;
        if ($user) {

            $docker = new Docker();
            $docker->setUser($user);
            $dockerForm = $this->createForm(DockerType::class, $docker, [
            ]);
            $dockerForm->handleRequest($request);
            if ($dockerForm->isSubmitted() && $dockerForm->isValid()) {
                $docker->setNumber($user->getDockers()->count() + 1);
                $this->entityManager->persist($docker);
                $this->entityManager->flush();
                $this->addFlash(
                    'success',
                    'Your docker has been uploaded.'
                );
                return $this->redirectToRoute('app_challenge');
            }

            $report = $this->entityManager->getRepository(Report::class)->findOneBy(['user' => $user]);
            if (!$report) {
                $report = new Report();
            }
            $reportForm = $this->createForm(ReportType::class, $report, [
            ]);
            $reportForm->handleRequest($request);
            if ($reportForm->isSubmitted() && $reportForm->isValid()) {

                $this->entityManager->persist($report);
                $this->entityManager->flush();
                $this->addFlash(
                    'success',
                    'Your technical report has been uploaded.'
                );
                return $this->redirectToRoute('app_challenge');
            }
        }
        return $this->render('challenge/challenge.html.twig', [
            'title' => 'Challenge',
            'dockerForm' => $dockerForm,
            'reportForm' => $reportForm,
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