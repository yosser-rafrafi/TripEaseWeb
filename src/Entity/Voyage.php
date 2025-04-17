<?php

namespace App\Entity;
use App\Entity\Mission;
use Symfony\Component\Validator\Constraints as Assert;

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

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: "La destination est obligatoire")]
    #[Assert\Length(max: 255, maxMessage: "La destination ne peut pas dépasser {{ limit }} caractères")]
    private ?string $destination = null;

    #[ORM\Column(type: 'date', nullable: false)]
    #[Assert\NotBlank(message: "La date de départ est obligatoire")]
    #[Assert\GreaterThan("today", message: "La date de départ doit être dans le futur")]
    private ?\DateTimeInterface $date_depart = null;

    
    #[ORM\Column(type: 'date', nullable: false)]
    #[Assert\NotBlank(message: "La date de retour est obligatoire")]
    #[Assert\Expression(
        "this.getDateRetour() > this.getDateDepart()",
        message: "La date de retour doit être après la date de départ"
    )]
    private ?\DateTimeInterface $date_retour = null;

    #[ORM\Column(type: 'integer', nullable: false)]
    #[Assert\NotBlank(message: "Le budget est obligatoire")]
    #[Assert\Positive(message: "Le budget doit être un nombre positif")]
    private ?int $budget = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $etat = null;


    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: "Le titre est obligatoire")]
    #[Assert\Length(max: 255, maxMessage: "Le titre ne peut pas dépasser {{ limit }} caractères")]
    private ?string $title = null;

    #[ORM\OneToMany(mappedBy: 'voyage', targetEntity: Mission::class, orphanRemoval: true)]
    private Collection $missions;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "userId", referencedColumnName: "id", nullable: true)]
    private ?User $user = null;


        

    #[ORM\Column(name: 'numeroVol', type: 'string', nullable: false)]
    #[Assert\NotBlank(message: "Le numéro de vol est obligatoire")]
    #[Assert\Regex(
        pattern: "/^[A-Z]{2}[0-9]{3,4}$/",
        message: "Le numéro de vol doit commencer par 2 lettres suivies de 3 ou 4 chiffres"
    )]
    private ?string $numeroVol = null;

    #[ORM\OneToMany(targetEntity: Flight::class, mappedBy: 'voyage')]
    private Collection $flights;
    
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'voyages')]
    #[ORM\JoinTable(name: 'voyage_user')]
    #[ORM\JoinColumn(name: 'voyage_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private Collection $users;

   

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addVoyage($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeVoyage($this);
        }

        return $this;
    }


    public function __construct()
    {
        $this->missions = new ArrayCollection();
        $this->flights = new ArrayCollection();
        $this->users = new ArrayCollection();

        
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }


    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): static
    {
        $this->destination = $destination;

        return $this;
    }

    public function getDateDepart(): ?\DateTimeInterface
    {
        return $this->date_depart;
    }
    
    public function setDateDepart(?\DateTimeInterface $date_depart): static
    {
        $this->date_depart = $date_depart;
        return $this;
    }
    
    public function getDateRetour(): ?\DateTimeInterface
    {
        return $this->date_retour;
    }
    
    public function setDateRetour(?\DateTimeInterface $date_retour): static
    {
        $this->date_retour = $date_retour;
        return $this;
    }

   
    
    
    public function getBudget(): ?int
    {
        return $this->budget;
    }

    public function setBudget(int $budget): static
    {
        $this->budget = $budget;

        return $this;
    }


    // Toujours stocké mais recalculable
    public function calculerEtat(): string
    {
        $aujourdhui = new \DateTime();
    
        if ($this-> date_retour < $aujourdhui) {
            return 'Terminé';
        } elseif ($this-> date_depart > $aujourdhui) {
            return 'Pas encore commencé';
        } else {
            return 'En cours';
        }
    }

    // Utilisé pour affichage dynamique
    public function getEtat(): string
    {
        return $this->calculerEtat();
    }

    public function setEtat(): static
    {
        $this->etat = $this->calculerEtat();

        return $this;
    }


    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    
    
    
    /**
     * @return Collection<int, Mission>
     */
    public function getMissions(): Collection
    {
        return $this->missions;
    }
    
    public function addMission(Mission $mission): static
    {
        if (!$this->missions->contains($mission)) {
            $this->missions[] = $mission;
            $mission->setVoyage($this);
        }
    
        return $this;
    }
    
    public function removeMission(Mission $mission): static
    {
        if ($this->missions->removeElement($mission)) {
            // set the owning side to null (unless already changed)
            if ($mission->getVoyage() === $this) {
                $mission->setVoyage(null);
            }
        }
    
        return $this;
    }

    
        
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }
    
    
    
    



    public function getNumeroVol(): ?string
    {
        return $this->numeroVol;
    }

    public function setNumeroVol(string $numeroVol): static
    {
        $this->numeroVol = $numeroVol;

        return $this;
    }

    


    /**
     * @return Collection<int, Flight>
     */
    public function getFlights(): Collection
    {
        return $this->flights;
    }

    public function addFlight(Flight $flight): static
    {
        if (!$this->flights->contains($flight)) {
            $this->flights->add($flight);
            $flight->setVoyage($this);
        }

        return $this;
    }

    public function removeFlight(Flight $flight): static
    {
        if ($this->flights->removeElement($flight)) {
            // set the owning side to null (unless already changed)
            if ($flight->getVoyage() === $this) {
                $flight->setVoyage(null);
            }
        }

        return $this;
    }

   

    
        
    
       
       
    
}
