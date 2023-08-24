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
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

class ChallengeController extends AbstractController
{
    public const MAX_NUMBER_OF_SUBMISSIONS = 3;
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
                $docker->setVersion($user->getDockers()->count() + 1);
                $this->entityManager->persist($docker);
                $this->entityManager->flush();

                // Send an email to the user
                $email = (new TemplatedEmail())
                    ->from(new Address('dginhac@u-bourgogne.fr', 'Atlas Challenge'))
                    ->to($user->getEmail())
                    ->addCc('atlas-challenge-l@u-bourgogne.fr')
                    ->subject('Atlas Challenge: Your Docker Archive has been submitted.')
                    ->textTemplate('emails/docker-has-been-submitted.txt.twig')
                    ->htmlTemplate('emails/docker-has-been-submitted.html.twig')
                    ->context(['user' => $user]);
                $mailer->send($email);

                $this->addFlash(
                    'success',
                    'Your docker has been uploaded. If your Docker archive is successfully validated on the 
                    test dataset, you will automatically be notified by email when your evaluation metrics are 
                    published on the leaderboard.
'
                );
                return $this->redirectToRoute('app_challenge');
            }


            $report = new Report();
            $report->setUser($user);
            $reportForm = $this->createForm(ReportType::class, $report, [
            ]);
            $reportForm->handleRequest($request);
            if ($reportForm->isSubmitted() && $reportForm->isValid()) {


                $oldReport = $this->entityManager->getRepository(Report::class)->findOneBy([
                    'user' => $user,
                ]);
                if ($oldReport) {
                    $this->entityManager->remove($oldReport);
                }
                $this->entityManager->persist($report);
                $this->entityManager->flush();

                // Send an email to the user
                $email = (new TemplatedEmail())
                    ->from(new Address('dginhac@u-bourgogne.fr', 'Atlas Challenge'))
                    ->to($user->getEmail())
                    ->addCc('atlas-challenge-l@u-bourgogne.fr')
                    ->subject('Atlas Challenge: Your Technical Report has been submitted.')
                    ->textTemplate('emails/report-has-been-submitted.txt.twig')
                    ->htmlTemplate('emails/report-has-been-submitted.html.twig')
                    ->context(['user' => $user]);
                $mailer->send($email);

                $this->addFlash(
                    'success',
                    'Your Technical Report has been uploaded. If necessary, you can revise and submit your 
                    Technical Report as many times as necessary up to the deadline.'
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
        $user->setDataset(true);
        $entityManager->persist($user);
        $entityManager->flush();

        $docker = 'downloads/atlas-docker-1.0.1.zip';
        return $this->file($docker);
    }
}