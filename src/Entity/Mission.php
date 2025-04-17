<?php
namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;


use App\Repository\MissionRepository;

#[ORM\Entity(repositoryClass: MissionRepository::class)]
#[ORM\Table(name: 'mission')]
class Mission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', nullable: true)]
    #[Assert\NotBlank(message: 'Le titre ne peut pas être vide.')]
    #[Assert\Length(max: 255, maxMessage: 'Le titre ne peut pas dépasser 255 caractères.')]
    private ?string $title = null;

    #[ORM\Column(type: 'string', nullable: true)]
    #[Assert\NotBlank(message: 'Le lieu est requis.')]
    private ?string $lieu = null;

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $description = null;

    

    // dat de debut mission

    #[ORM\Column(name:'dateDebut', type: 'datetime', nullable: true)]
    #[Assert\NotNull(message: 'La date de début est obligatoire.')]
    #[Assert\Expression(
        "this.getDateDebut() >= this.getVoyage().getDateDepart()",
        message: "La date de début doit être après ou égale à la date de départ du voyage")]
    #[Assert\Type(\DateTimeInterface::class)]
    private ?\DateTimeInterface $dateDebut = null;



    // date Fin mission

    #[ORM\Column(name:'dateFin', type: 'datetime', nullable: true)]
    #[Assert\NotNull(message: 'La date de fin est obligatoire.')]
    #[Assert\Expression(
        "this.getDateFin() <= this.getVoyage().getDateRetour()",
        message: "La date de fin doit être avant ou égale à la date de retour du voyage")]
    #[Assert\Expression(
        "this.getDateDebut() <= this.getDateFin()",
        message: "La date de début doit être avant la date de fin")]
    #[Assert\Type(\DateTimeInterface::class)] 
    private ?\DateTimeInterface $dateFin = null;



    //type

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $type = null;

    // duree

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $duree = null;


    // Ajout de la relation avec Voyage
    #[ORM\ManyToOne(targetEntity: Voyage::class, inversedBy: 'missions')]
    #[ORM\JoinColumn(name: 'voyageId', referencedColumnName: 'id', nullable: false)]
    private ?Voyage $voyage = null;

    // Ajout de la relation avec User
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'userId', referencedColumnName: 'id', nullable: true)]
    private ?User $user = null;

    
    
    public function getId(): ?int
    {
        return $this->id;
        
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        $this->calculerDureeEtType();
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;
        return $this;
    }


    public function getLieu(): ?string
    {
        return $this->lieu;
    }

 
    public function setLieu(?string $lieu): self
    {
        $this->lieu = $lieu;
        return $this;
    }


    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;
        return $this;
    }


    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;
        return $this;
    }

    public function calculerDureeEtType(): void
    {
        if (!$this->dateDebut || !$this->dateFin) {
            return;
        }
    
        $interval = $this->dateDebut->diff($this->dateFin);
        $heures = ($interval->days * 24) + $interval->h;
    
        if ($heures < 24) {
            $this->type = 'Courte';
            $this->duree = $heures . ' heures';
        } else {
            $this->type = 'Longue';
            $this->duree = $interval->days . ' jours';
        }
    }


    public function getType(): ?string
    {
        $this->calculerDureeEtType();
        return $this->type;
    }

   
     

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function onSave(): void
    {
        $this->calculerDureeEtType();
    }

    public function getDuree(): ?string
    {
        $this->calculerDureeEtType();
        return $this->duree;
    }



    
    public function getVoyage(): ?Voyage
    {
        return $this->voyage;
    }

    public function setVoyage(?Voyage $voyage): self
    {
        $this->voyage = $voyage;
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
}

