<?php

namespace App\Controller;

use App\Entity\Docker;
use App\Entity\Metrics;
use App\Entity\Report;
use App\Entity\User;
use App\Form\MetricsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use League\Csv\Reader;

class AdminController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/admin', name: 'app_admin')]
    public function index(Request $request): Response
    {
        $dockers = $this->entityManager->getRepository(Docker::class)->findBy([], ['createdAt' => 'DESC']);
        $reports = $this->entityManager->getRepository(Report::class)->findBy([], ['createdAt' => 'DESC']);

        return $this->render('admin/admin.html.twig', [
            'title' => 'Admin zone',
            'dockers' => $dockers,
            'reports' => $reports,
        ]);
    }

    #[Route('/admin/docker/{id}/metrics', name: 'app_admin_metrics')]
    public function metrics(Docker $docker, Request $request) : Response
    {
        $metrics = new Metrics();
        $metrics->setDocker($docker);
        $metricsForm = $this->createForm(MetricsType::class, $metrics, [
        ]);

        $metricsForm->handleRequest($request);
        if ($metricsForm->isSubmitted() && $metricsForm->isValid()) {
            $csv = Reader::createFromPath($metrics->getMetricsFile(), 'r');
            $csv->setDelimiter(',');
            $records = $csv->getRecords();
            foreach ($records as $offset => $record) {
                $metrics->setLiverASD($record[0]);
                $metrics->setLiverDice($record[1]);
                $metrics->setLiverHausdorffDistance($record[2]);
                $metrics->setLiverSurfaceDice($record[3]);
                $metrics->setTumorASD($record[4]);
                $metrics->setTumorDice($record[5]);
                $metrics->setTumorHausdorffDistance($record[6]);
                $metrics->setTumorSurfaceDice($record[7]);
                $metrics->setRmse($record[8]);
            }
            $metrics->getDocker()->setProcessed(true);
            $this->entityManager->persist($metrics);
            //$this->entityManager->flush();

            $allMetrics = $this->entityManager->getRepository(Metrics::class)->findAll();
            dump($allMetrics);
            foreach ($allMetrics as $metrics) {
                $liverASD[$metrics->getDocker()->getId()] = $metrics->getLiverASD();
                $liverDice[$metrics->getDocker()->getId()] = $metrics->getLiverDice();
                $liverHausdorffDistance[$metrics->getDocker()->getId()] = $metrics->getLiverHausdorffDistance();
                $liverSurfaceDice[$metrics->getDocker()->getId()] = $metrics->getLiverSurfaceDice();
                $tumorASD[$metrics->getDocker()->getId()] = $metrics->getTumorASD();
                $tumorDice[$metrics->getDocker()->getId()] = $metrics->getTumorDice();
                $tumorHausdorffDistance[$metrics->getDocker()->getId()] = $metrics->getTumorHausdorffDistance();
                $tumorSurfaceDice[$metrics->getDocker()->getId()] = $metrics->getTumorSurfaceDice();
                $rmse[$metrics->getDocker()->getId()] = $metrics->getRmse();
            }
            $liverASDsort = $liverASD;
            arsort($liverASDsort);
            dump($liverASDsort);
            $liverASDrank = array();
            $rank = 0;
            $lastValue = null;
            foreach ($liverASDsort as $key => $value) {
                if ($value != $lastValue) {
                    $lastValue = $value;
                    $rank++;
                }
                $liverASDrank[$key] = $rank;
            }
            dd($liverASDrank);
            $liverDiceRank = $liverDice;
            asort($liverDiceRank);
            $liverHausdorffDistanceRank = $liverHausdorffDistance;
            arsort($liverHausdorffDistanceRank);
            $liverSurfaceDiceRank = $liverSurfaceDice;
            asort($liverSurfaceDiceRank);
            $tumorASDRank = $tumorASD;
            arsort($tumorASDRank);
            $tumorDiceRank = $tumorDice;
            asort($tumorDiceRank);
            $tumorHausdorffDistanceRank = $tumorHausdorffDistance;
            arsort($tumorHausdorffDistanceRank);
            $tumorSurfaceDiceRank = $tumorSurfaceDice;
            asort($tumorSurfaceDiceRank);
            $rmseRank = $rmse;
            asort($rmseRank);

            $allMetricsRank =


            $this->addFlash('success', 'Metrics file has been uploaded.');
            return $this->redirectToRoute('app_admin');
        }
        return $this->render('admin/metrics.html.twig', [
            'title' => 'Metrics',
            'metricsForm' => $metricsForm,
        ]);

    }

}