<?php

namespace App\Entity;

use App\Repository\SubmissionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: SubmissionRepository::class)]
#[Vich\Uploadable]
#[ORM\HasLifecycleCallbacks]

class Submission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'submissions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $User = null;

    #[ORM\PrePersist]
    public function prePersist(): void {
        if(empty($this->createdAt)) {
            $this->createdAt = new \DateTimeImmutable();
            $this->number++;
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

    #[Vich\UploadableField(mapping: 'submission', fileNameProperty: 'zipName', size: 'zipSize')]
    private ?File $zipFile = null;

    #[ORM\Column(type: 'string')]
    private ?string $zipName = null;

    #[ORM\Column(type: 'integer')]
    private ?int $zipSize = null;


    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $zipFile
     */
    public function setZipFile(?File $zipFile = null): void
    {
        $this->zipFile = $zipFile;

        if (null !== $zipFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            //$this->createdAt = new \DateTimeImmutable();

        }
    }

    public function getZipFile(): ?File
    {
        return $this->zipFile;
    }

    public function setZipName(?string $zipName): void
    {
        $this->zipName = $zipName;
    }

    public function getZipName(): ?string
    {
        return $this->zipName;
    }

    public function setZipSize(?int $zipSize): void
    {
        $this->zipSize = $zipSize;
    }

    public function getZipSize(): ?int
    {
        return $this->zipSize;
    }







    #[Vich\UploadableField(mapping: 'submission', fileNameProperty: 'reportName', size: 'reportSize')]
    private ?File $reportFile = null;

    #[ORM\Column(type: 'string')]
    private ?string $reportName = null;

    #[ORM\Column(type: 'integer')]
    private ?int $reportSize = null;

    #[ORM\Column]
    private ?int $number = null;


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








    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }
}