<?php
namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
<<<<<<< HEAD
=======
use Symfony\Component\Validator\Constraints as Assert;

>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed

use App\Repository\MissionRepository;

#[ORM\Entity(repositoryClass: MissionRepository::class)]
#[ORM\Table(name: 'mission')]
class Mission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

<<<<<<< HEAD

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $title = null;

    
    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $lieu = null;

    
    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $description = null;

    

    #[ORM\Column(name:'dateDebut', type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $dateDebut = null;
    
    #[ORM\Column(name:'dateFin', type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $dateFin = null;

    

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $type = null;

    

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $duree = null;


=======
    #[ORM\Column(type: 'string', nullable: true)]
    #[Assert\NotBlank(message: 'Le titre ne peut pas être vide.')]
    #[Assert\Length(max: 255, maxMessage: 'Le titre ne peut pas dépasser 255 caractères.')]
    private ?string $title = null;

    #[ORM\Column(type: 'string', nullable: true)]
    #[Assert\NotBlank(message: 'Le lieu est requis.')]
    private ?string $lieu = null;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: 'La description est requise.')]
    private ?string $description = null;

    #[ORM\Column(name:'dateDebut', type: 'datetime', nullable: true)]
    #[Assert\NotNull(message: 'La date de début est obligatoire.')]
    #[Assert\Type(\DateTimeInterface::class)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(name:'dateFin', type: 'datetime', nullable: true)]
    #[Assert\NotNull(message: 'La date de fin est obligatoire.')]
    #[Assert\Type(\DateTimeInterface::class)]
    
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: 'Le type de mission est requis.')]
    private ?string $type = null;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: 'La durée est requise.')]
    private ?string $duree = null;



>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed
    #[ORM\Column(name:'voyageId', type: 'integer', nullable: false)]
    private ?int $voyageId = null;

    #[ORM\Column(name:'userId', type: 'integer', nullable: true)]
    private ?int $userId = null;


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

<<<<<<< HEAD
    public function setLieu(string $lieu): self
=======
 
    public function setLieu(?string $lieu): self
>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed
    {
        $this->lieu = $lieu;
        return $this;
    }


<<<<<<< HEAD
=======

>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed
    public function getDescription(): ?string
    {
        return $this->description;
    }

<<<<<<< HEAD
    public function setDescription(string $description): self
=======
    public function setDescription(?string $description): self
>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed
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

    public function getType(): ?string
    {
        return $this->type;
    }

<<<<<<< HEAD
    public function setType(string $type): self
=======
    public function setType(?string $type): self
>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed
    {
        $this->type = $type;
        return $this;
    }
    public function getDuree(): ?string
    {
        return $this->duree;
    }

<<<<<<< HEAD
    public function setDuree(string $duree): self
=======
    public function setDuree(?string $duree): self
>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed
    {
        $this->duree = $duree;
        return $this;
    }

    public function getVoyageId(): ?int
    {
        return $this->voyageId;
    }

    public function setVoyageId(int $voyageId): self
    {
        $this->voyageId = $voyageId;
        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): self
    {
        $this->userId = $userId;
        return $this;
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

