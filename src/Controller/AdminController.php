<?php

namespace App\Controller;

use App\Entity\Docker;
use App\Entity\Metrics;
use App\Entity\Report;
use App\Entity\User;
use App\Form\MetricsType;
use App\Service\Data\Ranking;
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

        $rawMetrics = $this->entityManager->getRepository(Metrics::class)->findAll();

        foreach ($rawMetrics as $metrics) {
            $liverASD[$metrics->getDocker()->getId()] = $metrics->getLiverASD();
            $liverDice[$metrics->getDocker()->getId()] = $metrics->getLiverDice();
            $liverHausdorffDistance[$metrics->getDocker()->getId()] = $metrics->getLiverHausdorffDistance();
            $liverSurfaceDice[$metrics->getDocker()->getId()] = $metrics->getLiverSurfaceDice();
            $tumorASD[$metrics->getDocker()->getId()] = $metrics->getTumorASD();
            $tumorDice[$metrics->getDocker()->getId()] = $metrics->getTumorDice();
            $tumorHausdorffDistance[$metrics->getDocker()->getId()] = $metrics->getTumorHausdorffDistance();
            $tumorSurfaceDice[$metrics->getDocker()->getId()] = $metrics->getTumorSurfaceDice();
            $rmse[$metrics->getDocker()->getId()] = $metrics->getRmse();

            $allMetrics[$metrics->getDocker()->getId()] = [
                $metrics->getLiverASD(),
                $metrics->getLiverDice(),
                $metrics->getLiverHausdorffDistance(),
                $metrics->getLiverSurfaceDice(),
                $metrics->getTumorASD(),
                $metrics->getTumorDice(),
                $metrics->getTumorHausdorffDistance(),
                $metrics->getTumorSurfaceDice(),
                $metrics->getRmse()];
        }
        $ranking = new Ranking();
        $liverASDrank = $ranking->getRanking($liverASD, SORT_ASC);
        $liverDiceRank = $ranking->getRanking($liverDice, SORT_DESC);
        $liverHausdorffDistanceRank = $ranking->getRanking($liverHausdorffDistance, SORT_ASC);
        $liverSurfaceDiceRank = $ranking->getRanking($liverSurfaceDice, SORT_DESC);
        $tumorASDRank = $ranking->getRanking($tumorASD, SORT_ASC);
        $tumorDiceRank = $ranking->getRanking($tumorDice, SORT_DESC);
        $tumorHausdorffDistanceRank = $ranking->getRanking($tumorHausdorffDistance, SORT_ASC);
        $tumorSurfaceDiceRank = $ranking->getRanking($tumorSurfaceDice, SORT_DESC);
        $rmseRank = $ranking->getRanking($rmse, SORT_ASC);

        $allMetricsRank = [
            $liverASDrank,
            $liverDiceRank,
            $liverHausdorffDistanceRank,
            $liverSurfaceDiceRank,
            $tumorASDRank,
            $tumorDiceRank,
            $tumorHausdorffDistanceRank,
            $tumorSurfaceDiceRank,
            $rmseRank
        ];

        $rankSum = [];
        foreach ($liverASDrank as $dockerId => $rank) {
            $rankData[$dockerId] = array_column($allMetricsRank, $dockerId);
            $rankSum[$dockerId] = array_sum($rankData[$dockerId]);
        }

        $finalRanks = $ranking->getRanking($rankSum, SORT_ASC);
        asort($finalRanks);
        $finalRanks = array_keys($finalRanks);


        //dump($allMetrics);
        //dump($allMetricsRank);
        //dd($finalRanks);

        return $this->render('admin/admin.html.twig', [
            'title' => 'Admin zone',
            'dockers' => $dockers,
            'reports' => $reports,
            'rawMetrics' => $rawMetrics,
            'allMetrics' => $allMetrics,
            'allMetricsRank' => $allMetricsRank,
            'finalRanks' => $finalRanks,
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

            $this->addFlash('success', 'Metrics file has been uploaded.');
            return $this->redirectToRoute('app_admin');
        }
        return $this->render('admin/metrics.html.twig', [
            'title' => 'Metrics',
            'metricsForm' => $metricsForm,
        ]);

    }

}