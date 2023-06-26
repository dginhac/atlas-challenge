<?php

namespace App\Entity;

use App\Repository\DockerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
#use Vich\UploaderBundle\Entity\File;

use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: DockerRepository::class)]
#[Vich\Uploadable]
#[ORM\HasLifecycleCallbacks]

class Docker
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'dockers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(nullable: true)]
    private ?int $number = null;

    #[Vich\UploadableField(mapping: 'submission', fileNameProperty: 'dockerName', size: 'dockerSize')]
    private ?File $dockerFile = null;

    #[ORM\Column(type: 'string')]
    private ?string $dockerName = null;

    #[ORM\Column(type: 'integer')]
    private ?int $dockerSize = null;

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

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(?int $number): self
    {
        $this->number = $number;

        return $this;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $dockerFile
     */
    public function setDockerFile(?File $dockerFile = null): void
    {
        $this->dockerFile = $dockerFile;

        if (null !== $dockerFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            //$this->createdAt = new \DateTimeImmutable();

        }
    }

    public function getDockerFile(): ?File
    {
        return $this->dockerFile;
    }

    public function setDockerName(?string $dockerName): void
    {
        $this->dockerName = $dockerName;
    }

    public function getDockerName(): ?string
    {
        return $this->dockerName;
    }

    public function setDockerSize(?int $dockerSize): void
    {
        $this->dockerSize = $dockerSize;
    }

    public function getDockerSize(): ?int
    {
        return $this->dockerSize;
    }
}