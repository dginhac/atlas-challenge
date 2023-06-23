<?php

namespace App\Entity;

use App\Repository\ReportRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ReportRepository::class)]
#[Vich\Uploadable]
#[ORM\HasLifecycleCallbacks]

class Report
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToOne(inversedBy: 'technicalreport', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;


    #[Vich\UploadableField(mapping: 'submission', fileNameProperty: 'reportName', size: 'reportSize')]
    private ?File $reportFile = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $reportName = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $reportSize = null;

    #[ORM\PrePersist]
    public function prePersist(): void {
        if(empty($this->createdAt)) {
            $this->createdAt = new \DateTimeImmutable();
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }


    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $reportFile
     */
    public function setReportFile(?File $reportFile = null): void
    {
        $this->reportFile = $reportFile;

        if (null !== $reportFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            //$this->createdAt = new \DateTimeImmutable();

        }
    }

    public function getReportFile(): ?File
    {
        return $this->reportFile;
    }

    public function setReportName(?string $reportName): void
    {
        $this->reportName = $reportName;
    }

    public function getReportName(): ?string
    {
        return $this->reportName;
    }

    public function setReportSize(?int $reportSize): void
    {
        $this->reportSize = $reportSize;
    }

    public function getReportSize(): ?int
    {
        return $this->reportSize;
    }

}