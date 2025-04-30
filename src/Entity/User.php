<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\NotificationVoyage;

use App\Repository\UserRepository;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'user')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Voyage::class, mappedBy: 'users')]
    private Collection $voyages;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: NotificationVoyage::class)]
    private Collection $notifications;


    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotifications(NotificationVoyage $notification): static
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
        }

        return $this;
    }
    



    public function getVoyages(): Collection
    {
        return $this->voyages;
    }

    public function addVoyage(Voyage $voyage): static
    {
        if (!$this->voyages->contains($voyage)) {
            $this->voyages->add($voyage);
        }

        return $this;
    }

    public function removeVoyage(Voyage $voyage): static
    {
        $this->voyages->removeElement($voyage);

        return $this;
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

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $nom = null;

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $prenom = null;

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;
        return $this;
    }

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $identifiant = null;

    public function getIdentifiant(): ?string
    {
        return $this->identifiant;
    }

    public function setIdentifiant(?string $identifiant): static
    {
        $this->identifiant = $identifiant;
        return $this;
    }

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $role = null;

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): static
    {
        $this->role = $role;
        return $this;
    }

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;
        return $this;
    }

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;
        return $this;
    }

    #[ORM\Column(name: 'numTel', length: 255, nullable: true)]
    private ?string $numTel = null;

    public function getNumTel(): ?string
    {
        return $this->numTel;
    }

    public function setNumTel(?string $numTel): static
    {
        $this->numTel = $numTel;
        return $this;
    }

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $password = null;

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;
        return $this;
    }

    #[ORM\Column(name: 'photoDeProfile', length: 255, nullable: true)]
    private ?string $photoDeProfile = null;

    public function getPhotoDeProfile(): ?string
    {
        return $this->photoDeProfile;
    }

    public function setPhotoDeProfile(?string $photoDeProfile): static
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
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): static
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setUser($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getUser() === $this) {
                $commentaire->setUser(null);
            }
        }

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
        $this->statuts = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->voyages = new ArrayCollection();
        $this->notifications = new ArrayCollection();

    }

    /**
     * @return Collection<int, Statut>
     */
    public function getStatuts(): Collection
    {
        return $this->statuts;
    }

    public function addStatut(Statut $statut): static
    {
        if (!$this->statuts->contains($statut)) {
            $this->statuts->add($statut);
        }

        return $this;
    }

    public function removeStatut(Statut $statut): static
    {
        $this->statuts->removeElement($statut);

        return $this;
    }

    public function getRoles(): array
    {
        // Ensure we always return an array
        $roles = [];
        
        // Add the user's role with ROLE_ prefix
        if ($this->role) {
            $roles[] = 'ROLE_' . strtoupper($this->role);
        }
        
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        
        return array_unique($roles);
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    #[ORM\Column(type: 'boolean')]
    private $isActive = false;

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;
        return $this;
    }

    #[ORM\Column(length: 6, nullable: true)]
    private ?string $reset_password = null;

    public function getResetPassword(): ?string
    {
        return $this->reset_password;
    }

    public function setResetPassword(?string $reset_password): self
    {
        $this->reset_password = $reset_password;
        return $this;
    }
}