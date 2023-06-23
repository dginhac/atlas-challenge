<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(nullable: true)]
    private ?bool $dataset = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Docker::class)]
    private Collection $dockers;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Report $report = null;

    public function __construct()
    {
        $this->dockers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }



    #[ORM\PrePersist]
    #[ORM\PreUpdate]

    public function initUser()
    {
        if (empty($this->firstname)) {
            $this->setFirstname('');
        }
        if (empty($this->lastname)) {
            $this->setLastname('');
        }

        if (empty($this->isVerified)) {
            $this->setIsVerified(false);
        }

        if (empty($this->dataset)) {
            $this->setDataset(false);
        }

        $this->setRoles(['ROLE_USER']);
    }

    public function isDataset(): ?bool
    {
        return $this->dataset;
    }

    public function setDataset(?bool $dataset): self
    {
        $this->dataset = $dataset;

        return $this;
    }

    /**
     * @return Collection<int, Docker>
     */
    public function getDockers(): Collection
    {
        return $this->dockers;
    }

    public function addDocker(Docker $docker): self
    {
        if (!$this->dockers->contains($docker)) {
            $this->dockers->add($docker);
            $docker->setUser($this);
        }

        return $this;
    }

    public function removeDocker(Docker $docker): self
    {
        if ($this->dockers->removeElement($docker)) {
            // set the owning side to null (unless already changed)
            if ($docker->getUser() === $this) {
                $docker->setUser(null);
            }
        }

        return $this;
    }

    public function getReport(): ?Report
    {
        return $this->report;
    }

    public function setReport(Report $report): self
    {
        // set the owning side of the relation if necessary
        if ($report->getUser() !== $this) {
            $report->setUser($this);
        }

        $this->report = $report;

        return $this;
    }
}