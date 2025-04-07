<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\VoyageRepository;

#[ORM\Entity(repositoryClass: VoyageRepository::class)]
#[ORM\Table(name: 'voyage')]
class Voyage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $destination = null;

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): self
    {
        $this->destination = $destination;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $date_depart = null;

    public function getDate_depart(): ?\DateTimeInterface
    {
        return $this->date_depart;
    }

    public function setDate_depart(\DateTimeInterface $date_depart): self
    {
        $this->date_depart = $date_depart;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $date_retour = null;

    public function getDate_retour(): ?\DateTimeInterface
    {
        return $this->date_retour;
    }

    public function setDate_retour(\DateTimeInterface $date_retour): self
    {
        $this->date_retour = $date_retour;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $budget = null;

    public function getBudget(): ?int
    {
        return $this->budget;
    }

    public function setBudget(int $budget): self
    {
        $this->budget = $budget;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $etat = null;

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): self
    {
        $this->etat = $etat;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $title = null;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $userId = null;

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $numeroVol = null;

    public function getNumeroVol(): ?string
    {
        return $this->numeroVol;
    }

    public function setNumeroVol(string $numeroVol): self
    {
        $this->numeroVol = $numeroVol;
        return $this;
    }

    #[ORM\OneToMany(targetEntity: Flight::class, mappedBy: 'voyage')]
    private Collection $flights;

    public function __construct()
    {
        $this->flights = new ArrayCollection();
    }

    /**
     * @return Collection<int, Flight>
     */
    public function getFlights(): Collection
    {
        if (!$this->flights instanceof Collection) {
            $this->flights = new ArrayCollection();
        }
        return $this->flights;
    }

    public function addFlight(Flight $flight): self
    {
        if (!$this->getFlights()->contains($flight)) {
            $this->getFlights()->add($flight);
        }
        return $this;
    }

    public function removeFlight(Flight $flight): self
    {
        $this->getFlights()->removeElement($flight);
        return $this;
    }

    public function getDateDepart(): ?\DateTimeInterface
    {
        return $this->date_depart;
    }

    public function setDateDepart(\DateTimeInterface $date_depart): static
    {
        $this->date_depart = $date_depart;

        return $this;
    }

    public function getDateRetour(): ?\DateTimeInterface
    {
        return $this->date_retour;
    }

    public function setDateRetour(\DateTimeInterface $date_retour): static
    {
        $this->date_retour = $date_retour;

        return $this;
    }

}
