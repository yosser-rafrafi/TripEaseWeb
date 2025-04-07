<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\UserRepository;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'user')]
class User
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

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $nom = null;

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $prenom = null;

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $identifiant = null;

    public function getIdentifiant(): ?string
    {
        return $this->identifiant;
    }

    public function setIdentifiant(?string $identifiant): self
    {
        $this->identifiant = $identifiant;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $role = null;

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): self
    {
        $this->role = $role;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $adresse = null;

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $email = null;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $numTel = null;

    public function getNumTel(): ?string
    {
        return $this->numTel;
    }

    public function setNumTel(?string $numTel): self
    {
        $this->numTel = $numTel;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $password = null;

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $photoDeProfile = null;

    public function getPhotoDeProfile(): ?string
    {
        return $this->photoDeProfile;
    }

    public function setPhotoDeProfile(?string $photoDeProfile): self
    {
        $this->photoDeProfile = $photoDeProfile;
        return $this;
    }

    #[ORM\OneToMany(targetEntity: Commentaire::class, mappedBy: 'user')]
    private Collection $commentaires;

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        if (!$this->commentaires instanceof Collection) {
            $this->commentaires = new ArrayCollection();
        }
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->getCommentaires()->contains($commentaire)) {
            $this->getCommentaires()->add($commentaire);
        }
        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        $this->getCommentaires()->removeElement($commentaire);
        return $this;
    }

    #[ORM\OneToMany(targetEntity: Reservationtransport::class, mappedBy: 'user')]
    private Collection $reservationtransports;

    /**
     * @return Collection<int, Reservationtransport>
     */
    public function getReservationtransports(): Collection
    {
        if (!$this->reservationtransports instanceof Collection) {
            $this->reservationtransports = new ArrayCollection();
        }
        return $this->reservationtransports;
    }

    public function addReservationtransport(Reservationtransport $reservationtransport): self
    {
        if (!$this->getReservationtransports()->contains($reservationtransport)) {
            $this->getReservationtransports()->add($reservationtransport);
        }
        return $this;
    }

    public function removeReservationtransport(Reservationtransport $reservationtransport): self
    {
        $this->getReservationtransports()->removeElement($reservationtransport);
        return $this;
    }

    
    #[ORM\ManyToMany(targetEntity: Statut::class, inversedBy: 'users')]
    #[ORM\JoinTable(
        name: 'favorites',
        joinColumns: [
            new ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')
        ],
        inverseJoinColumns: [
            new ORM\JoinColumn(name: 'statut_id', referencedColumnName: 'id')
        ]
    )]
    private Collection $statuts;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
        $this->reservationtransports = new ArrayCollection();
        $this->statuts = new ArrayCollection();
    }

    /**
     * @return Collection<int, Statut>
     */
    public function getStatuts(): Collection
    {
        if (!$this->statuts instanceof Collection) {
            $this->statuts = new ArrayCollection();
        }
        return $this->statuts;
    }

    public function addStatut(Statut $statut): self
    {
        if (!$this->getStatuts()->contains($statut)) {
            $this->getStatuts()->add($statut);
        }
        return $this;
    }

    public function removeStatut(Statut $statut): self
    {
        $this->getStatuts()->removeElement($statut);
        return $this;
    }

    

}
