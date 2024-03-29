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

    static function cmpRank(Metrics $a, Metrics $b): bool
    {
        return $a->getRank() > $b->getRank();
    }

    #[Route('/admin', name: 'app_admin')]
    public function index(Request $request): Response
    {
        $dockers = $this->entityManager->getRepository(Docker::class)->findBy([], ['createdAt' => 'DESC']);
        $reports = $this->entityManager->getRepository(Report::class)->findBy([], ['createdAt' => 'DESC']);

        $rawMetrics = $this->entityManager->getRepository(Metrics::class)->findAll();
        $rawMetricsById = [];
        foreach ($rawMetrics as $metrics) {
            $rawMetricsById[$metrics->getId()] = $metrics;
        }

        $liverASD = [];
        $liverDice = [];
        $liverHausdorffDistance = [];
        $liverSurfaceDice = [];
        $tumorASD = [];
        $tumorDice = [];
        $tumorHausdorffDistance = [];
        $tumorSurfaceDice = [];
        $rmse = [];

        foreach ($rawMetricsById as $id => $metrics) {
            $liverASD[$id] = $metrics->getLiverASD();
            $liverDice[$id] = $metrics->getLiverDice();
            $liverHausdorffDistance[$id] = $metrics->getLiverHausdorffDistance();
            $liverSurfaceDice[$id] = $metrics->getLiverSurfaceDice();
            $tumorASD[$id] = $metrics->getTumorASD();
            $tumorDice[$id] = $metrics->getTumorDice();
            $tumorHausdorffDistance[$id] = $metrics->getTumorHausdorffDistance();
            $tumorSurfaceDice[$id] = $metrics->getTumorSurfaceDice();
            $rmse[$id] = $metrics->getRmse();
        }

        $ranking = new Ranking();
        $liverASDrank = $ranking->getRanking($liverASD, SORT_ASC);
        foreach ($liverASDrank as $id => $rank) {
            $rawMetricsById[$id]->setLiverASDRank($rank);
        }
        $liverDiceRank = $ranking->getRanking($liverDice, SORT_DESC);
        foreach ($liverDiceRank as $id => $rank) {
            $rawMetricsById[$id]->setLiverDiceRank($rank);
        }
        $liverHausdorffDistanceRank = $ranking->getRanking($liverHausdorffDistance, SORT_ASC);
        foreach ($liverHausdorffDistanceRank as $id => $rank) {
            $rawMetricsById[$id]->setLiverHausdorffDistanceRank($rank);
        }
        $liverSurfaceDiceRank = $ranking->getRanking($liverSurfaceDice, SORT_DESC);
        foreach ($liverSurfaceDiceRank as $id => $rank) {
            $rawMetricsById[$id]->setLiverSurfaceDiceRank($rank);
        }
        $tumorASDRank = $ranking->getRanking($tumorASD, SORT_ASC);
        foreach ($tumorASDRank as $id => $rank) {
            $rawMetricsById[$id]->setTumorASDRank($rank);
        }
        $tumorDiceRank = $ranking->getRanking($tumorDice, SORT_DESC);
        foreach ($tumorDiceRank as $id => $rank) {
            $rawMetricsById[$id]->setTumorDiceRank($rank);
        }
        $tumorHausdorffDistanceRank = $ranking->getRanking($tumorHausdorffDistance, SORT_ASC);
        foreach ($tumorHausdorffDistanceRank as $id => $rank) {
            $rawMetricsById[$id]->setTumorHausdorffDistanceRank($rank);
        }
        $tumorSurfaceDiceRank = $ranking->getRanking($tumorSurfaceDice, SORT_DESC);
        foreach ($tumorSurfaceDiceRank as $id => $rank) {
            $rawMetricsById[$id]->setTumorSurfaceDiceRank($rank);
        }
        $rmseRank = $ranking->getRanking($rmse, SORT_ASC);
        foreach ($rmseRank as $id => $rank) {
            $rawMetricsById[$id]->setRmseRank($rank);
        }

        $rankSum = [];
        foreach ($rawMetricsById as $id => $metrics) {
            $rankSum[$id] = $metrics->getLiverASDRank() + $metrics->getLiverDiceRank() +
                $metrics->getLiverHausdorffDistanceRank() + $metrics->getLiverSurfaceDiceRank() +
                $metrics->getTumorASDRank() + $metrics->getTumorDiceRank() +
                $metrics->getTumorHausdorffDistanceRank() + $metrics->getTumorSurfaceDiceRank() +
                $metrics->getRmseRank();
        }
        $finalRanks = $ranking->getRanking($rankSum, SORT_ASC);
        asort($finalRanks);

        foreach ($finalRanks as $id => $rank) {
            $rawMetricsById[$id]->setRank($rank);
        }
        usort($rawMetricsById, [AdminController::class, 'cmpRank']);
        $leaderboard = $rawMetricsById;


        return $this->render('admin/admin.html.twig', [
            'title' => 'Admin zone',
            'dockers' => $dockers,
            'reports' => $reports,
            'leaderboard' => $leaderboard,
        ]);
    }

    #[Route('/admin/metrics/{id}/delete', name: 'app_admin_metrics_delete')]
    public function delete_metrics(Metrics $metrics, Request $request) : Response {

        $this->entityManager->remove($metrics);

        $docker = $metrics->getDocker();
        $docker->setProcessed(false);
        $this->entityManager->persist($docker);

        $this->entityManager->flush();

        $this->addFlash('success', 'Docker ' . $metrics->getDocker()->getId() . ': Metrics have been deleted. You can now upload new metrics.');
        return $this->redirectToRoute('app_admin');
    }

    #[Route('/admin/docker/{id}/metrics', name: 'app_admin_docker_metrics')]
    public function metrics(Docker $docker, Request $request) : Response
    {
        $metrics = new Metrics();
        $metrics->setDocker($docker);
        $metricsForm = $this->createForm(MetricsType::class, $metrics, [
        ]);

        $metricsForm->handleRequest($request);
        if ($metricsForm->isSubmitted() && $metricsForm->isValid()) {
            $csv = Reader::createFromPath($metrics->getMetricsFile(), 'r');
            $csv->setDelimiter(';');
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
            $this->entityManager->flush();

            $this->addFlash('success', 'Docker ' . $metrics->getDocker()->getId() . ': Metrics have been uploaded. ');
            return $this->redirectToRoute('app_admin');
        }
        return $this->render('admin/metrics.html.twig', [
            'title' => 'Metrics',
            'metricsForm' => $metricsForm,

        ]);

    }

    #[Route('/admin/docker/{id}/delete', name: 'app_admin_docker_delete')]
    public function delete_docker(Docker $docker, Request $request) : Response
    {
        $this->entityManager->remove($docker);
        $this->entityManager->flush();

        $this->addFlash('success', 'Docker ' . $docker->getId() . ' has been deleted.');
        return $this->redirectToRoute('app_admin');
    }


}