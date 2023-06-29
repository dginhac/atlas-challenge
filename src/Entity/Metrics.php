<?php

namespace App\Entity;

use App\Repository\MetricsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;



#[ORM\Entity(repositoryClass: MetricsRepository::class)]
#[Vich\Uploadable]

class Metrics
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $liverASD = null;
    private ?int $liverASDRank = null;

    #[ORM\Column]
    private ?float $liverDice = null;
    private ?int $liverDiceRank = null;

    #[ORM\Column]
    private ?float $liverHausdorffDistance = null;
    private ?int $liverHausdorffDistanceRank = null;

    #[ORM\Column]
    private ?float $liverSurfaceDice = null;
    private ?int $liverSurfaceDiceRank = null;

    #[ORM\Column]
    private ?float $tumorASD = null;
    private ?int $tumorASDRank = null;

    #[ORM\Column]
    private ?float $tumorDice = null;
    private ?int $tumorDiceRank = null;

    #[ORM\Column]
    private ?float $tumorHausdorffDistance = null;
    private ?int $tumorHausdorffDistanceRank = null;

    #[ORM\Column]
    private ?float $tumorSurfaceDice = null;
    private ?int $tumorSurfaceDiceRank = null;

    #[ORM\Column]
    private ?float $rmse = null;
    private ?int $rmseRank = null;

    private ?int $rank = null;

    #[ORM\OneToOne(inversedBy: 'metrics', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Docker $docker = null;

    #[Vich\UploadableField(mapping: 'submission', fileNameProperty: 'metricsName', size: 'metricsSize')]
    private ?File $metricsFile = null;

    #[ORM\Column(type: 'string')]
    private ?string $metricsName = null;

    #[ORM\Column(type: 'integer')]
    private ?int $metricsSize = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLiverASD(): ?float
    {
        return $this->liverASD;
    }

    public function setLiverASD(float $liverASD): self
    {
        $this->liverASD = $liverASD;

        return $this;
    }

    public function getLiverASDRank(): ?int
    {
        return $this->liverASDRank;
    }


    public function setLiverASDRank(int $liverASDRank): self
    {
        $this->liverASD = $liverASDRank;

        return $this;
    }

    public function getLiverDice(): ?float
    {
        return $this->liverDice;
    }

    public function getLiverDiceRank(): ?int
    {
        return $this->liverDiceRank;
    }

    public function setLiverDice(float $liverDice): self
    {
        $this->liverDice = $liverDice;

        return $this;
    }
    public function setLiverDiceRank(int $liverDiceRank): self
    {
        $this->liverDiceRank = $liverDiceRank;

        return $this;
    }

    public function getLiverHausdorffDistance(): ?float
    {
        return $this->liverHausdorffDistance;
    }
    public function getLiverHausdorffDistanceRank(): ?int
    {
        return $this->liverHausdorffDistanceRank;
    }

    public function setLiverHausdorffDistance(float $liverHausdorffDistance): self
    {
        $this->liverHausdorffDistance = $liverHausdorffDistance;

        return $this;
    }
    public function setLiverHausdorffDistanceRank(int $liverHausdorffDistanceRank): self
    {
        $this->liverHausdorffDistanceRank = $liverHausdorffDistanceRank;

        return $this;
    }

    public function getLiverSurfaceDice(): ?float
    {
        return $this->liverSurfaceDice;
    }
    public function getLiverSurfaceDiceRank(): ?int
    {
        return $this->liverSurfaceDiceRank;
    }

    public function setLiverSurfaceDice(float $liverSurfaceDice): self
    {
        $this->liverSurfaceDice = $liverSurfaceDice;

        return $this;
    }
    public function setLiverSurfaceDiceRank(int $liverSurfaceDiceRank): self
    {
        $this->liverSurfaceDiceRank = $liverSurfaceDiceRank;

        return $this;
    }

    public function getTumorASD(): ?float
    {
        return $this->tumorASD;
    }
    public function getTumorASDRank(): ?int
    {
        return $this->tumorASDRank;
    }

    public function setTumorASD(float $tumorASD): self
    {
        $this->tumorASD = $tumorASD;

        return $this;
    }
    public function setTumorASDRank(int $tumorASDRank): self
    {
        $this->tumorASDRank = $tumorASDRank;

        return $this;
    }

    public function getTumorDice(): ?float
    {
        return $this->tumorDice;
    }
    public function getTumorDiceRank(): ?int
    {
        return $this->tumorDiceRank;
    }

    public function setTumorDice(float $tumorDice): self
    {
        $this->tumorDice = $tumorDice;

        return $this;
    }
    public function setTumorDiceRank(int $tumorDiceRank): self
    {
        $this->tumorDiceRank = $tumorDiceRank;

        return $this;
    }

    public function getTumorHausdorffDistance(): ?float
    {
        return $this->tumorHausdorffDistance;
    }
    public function getTumorHausdorffDistanceRank(): ?int
    {
        return $this->tumorHausdorffDistanceRank;
    }

    public function setTumorHausdorffDistance(float $tumorHausdorffDistance): self
    {
        $this->tumorHausdorffDistance = $tumorHausdorffDistance;

        return $this;
    }
    public function setTumorHausdorffDistanceRank(int $tumorHausdorffDistanceRank): self
    {
        $this->tumorHausdorffDistanceRank = $tumorHausdorffDistanceRank;

        return $this;
    }

    public function getTumorSurfaceDice(): ?float
    {
        return $this->tumorSurfaceDice;
    }
    public function getTumorSurfaceDiceRank(): ?int
    {
        return $this->tumorSurfaceDiceRank;
    }

    public function setTumorSurfaceDice(float $tumorSurfaceDice): self
    {
        $this->tumorSurfaceDice = $tumorSurfaceDice;

        return $this;
    }
    public function setTumorSurfaceDiceRank(int $tumorSurfaceDiceRank): self
    {
        $this->tumorSurfaceDiceRank = $tumorSurfaceDiceRank;

        return $this;
    }

    public function getRmse(): ?float
    {
        return $this->rmse;
    }
    public function getRmseRank(): ?int
    {
        return $this->rmseRank;
    }

    public function setRmse(float $rmse): self
    {
        $this->rmse = $rmse;

        return $this;
    }
    public function setRmseRank(int $rmseRank): self
    {
        $this->rmseRank = $rmseRank;

        return $this;
    }

    public function getRank(): ?int
    {
        return $this->rank;
    }

    public function setRank(int $rank): self
    {
        $this->rank = $rank;

        return $this;
    }


    public function getDocker(): ?Docker
    {
        return $this->docker;
    }

    public function setDocker(Docker $docker): self
    {
        $this->docker = $docker;

        return $this;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $metricsFile
     */


    public function setMetricsFile(?File $metricsFile = null): void
    {
        $this->metricsFile = $metricsFile;

        if (null !== $metricsFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            //$this->createdAt = new \DateTimeImmutable();

        }
    }

    public function getMetricsFile(): ?File
    {
        return $this->metricsFile;
    }

    public function setMetricsName(?string $metricsName): void
    {
        $this->metricsName = $metricsName;
    }

    public function getMetricsName(): ?string
    {
        return $this->metricsName;
    }

    public function setMetricsSize(?int $metricsSize): void
    {
        $this->metricsSize = $metricsSize;
    }

    public function getMetricsSize(): ?int
    {
        return $this->metricsSize;
    }


}
