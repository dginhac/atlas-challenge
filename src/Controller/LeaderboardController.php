<?php

namespace App\Controller;

use App\Entity\Metrics;
use App\Service\Data\Ranking;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LeaderboardController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/leaderboard', name: 'app_leaderboard')]
    public function index(): Response
    {
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

        return $this->render('leaderboard/leaderboard.html.twig', [
            'title' => 'Leaderboard',
            'leaderboard' => $leaderboard,
        ]);
    }
}