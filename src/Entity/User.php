<?php

namespace App\Entity;
<<<<<<< HEAD

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use App\Repository\UserRepository;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'user')]
=======
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\UserRepository;

#[ORM\Entity(repositoryClass: UserRepository::class)]
>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

<<<<<<< HEAD
    #[ORM\ManyToMany(targetEntity: Voyage::class, mappedBy: 'users')]
    private Collection $voyages;

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
=======
    #[ORM\Column(length: 30, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $identifiant = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $role = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(name: 'numTel', length: 255, nullable: true)]
    private ?string $numTel = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $password = null;

    #[ORM\Column(name: 'photoDeProfile', length: 255, nullable: true)]
    private ?string $photoDeProfile = null;
>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed

    public function getId(): ?int
    {
        return $this->id;
    }

<<<<<<< HEAD
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $nom = null;

=======
>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed
    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

<<<<<<< HEAD
    #[ORM\Column(length: 30, nullable: true)]
    private ?string $prenom = null;

=======
>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed
    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;
        return $this;
    }

<<<<<<< HEAD
    #[ORM\Column(length: 30, nullable: true)]
    private ?string $identifiant = null;

=======
>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed
    public function getIdentifiant(): ?string
    {
        return $this->identifiant;
    }

    public function setIdentifiant(?string $identifiant): static
    {
        $this->identifiant = $identifiant;
        return $this;
    }

<<<<<<< HEAD
    #[ORM\Column(length: 10, nullable: true)]
    private ?string $role = null;

=======
>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed
    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): static
    {
        $this->role = $role;
        return $this;
    }

<<<<<<< HEAD
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

=======
>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed
    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;
        return $this;
    }

<<<<<<< HEAD
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

=======
>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;
        return $this;
    }

<<<<<<< HEAD
    #[ORM\Column(name: 'numTel', length: 255, nullable: true)]
    private ?string $numTel = null;

=======
>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed
    public function getNumTel(): ?string
    {
        return $this->numTel;
    }

    public function setNumTel(?string $numTel): static
    {
        $this->numTel = $numTel;
        return $this;
    }

<<<<<<< HEAD
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $password = null;

=======
>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;
        return $this;
    }

<<<<<<< HEAD
    #[ORM\Column(name: 'photoDeProfile', length: 255, nullable: true)]
    private ?string $photoDeProfile = null;

=======
>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed
    public function getPhotoDeProfile(): ?string
    {
        return $this->photoDeProfile;
    }

    public function setPhotoDeProfile(?string $photoDeProfile): static
    {
        $this->photoDeProfile = $photoDeProfile;
        return $this;
    }

<<<<<<< HEAD
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

=======
    public function getRoles(): array
    {
        return [$this->role];
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }


>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed
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

<<<<<<< HEAD
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
}
=======
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

    #[ORM\ManyToMany(targetEntity: Voyage::class, mappedBy: 'users')]
    private Collection $voyages;

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




} 
    

>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed
