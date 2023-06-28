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

    #[ORM\Column]
    private ?float $liverDice = null;

    #[ORM\Column]
    private ?float $liverHausdorffDistance = null;

    #[ORM\Column]
    private ?float $liverSurfaceDice = null;

    #[ORM\Column]
    private ?float $tumorASD = null;

    #[ORM\Column]
    private ?float $tumorDice = null;

    #[ORM\Column]
    private ?float $tumorHausdorffDistance = null;

    #[ORM\Column]
    private ?float $tumorSurfaceDice = null;

    #[ORM\Column]
    private ?float $rmse = null;

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

    public function getLiverDice(): ?float
    {
        return $this->liverDice;
    }

    public function setLiverDice(float $liverDice): self
    {
        $this->liverDice = $liverDice;

        return $this;
    }

    public function getLiverHausdorffDistance(): ?float
    {
        return $this->liverHausdorffDistance;
    }

    public function setLiverHausdorffDistance(float $liverHausdorffDistance): self
    {
        $this->liverHausdorffDistance = $liverHausdorffDistance;

        return $this;
    }

    public function getLiverSurfaceDice(): ?float
    {
        return $this->liverSurfaceDice;
    }

    public function setLiverSurfaceDice(float $liverSurfaceDice): self
    {
        $this->liverSurfaceDice = $liverSurfaceDice;

        return $this;
    }

    public function getTumorASD(): ?float
    {
        return $this->tumorASD;
    }

    public function setTumorASD(float $tumorASD): self
    {
        $this->tumorASD = $tumorASD;

        return $this;
    }

    public function getTumorDice(): ?float
    {
        return $this->tumorDice;
    }

    public function setTumorDice(float $tumorDice): self
    {
        $this->tumorDice = $tumorDice;

        return $this;
    }

    public function getTumorHausdorffDistance(): ?float
    {
        return $this->tumorHausdorffDistance;
    }

    public function setTumorHausdorffDistance(float $tumorHausdorffDistance): self
    {
        $this->tumorHausdorffDistance = $tumorHausdorffDistance;

        return $this;
    }

    public function getTumorSurfaceDice(): ?float
    {
        return $this->tumorSurfaceDice;
    }

    public function setTumorSurfaceDice(float $tumorSurfaceDice): self
    {
        $this->tumorSurfaceDice = $tumorSurfaceDice;

        return $this;
    }

    public function getRmse(): ?float
    {
        return $this->rmse;
    }

    public function setRmse(float $rmse): self
    {
        $this->rmse = $rmse;

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
