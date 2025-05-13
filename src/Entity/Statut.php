<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\StatutRepository;

#[ORM\Entity(repositoryClass: StatutRepository::class)]
#[ORM\Table(name: 'statut')]
class Statut
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'statuts')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private ?User $user = null;

    #[ORM\Column(type: 'text', nullable: false)]
    #[Assert\NotBlank(message: "Le contenu du statut ne peut pas être vide")]
    #[Assert\Length(min: 1, minMessage: "Le contenu doit contenir au moins 1 caractère")]
    private ?string $contenu = null;

    #[ORM\Column(type: 'text', nullable: false)]
    #[Assert\NotBlank(message: "Le type de contenu ne peut pas être vide")]
    #[Assert\Length(min: 1, minMessage: "Le type de contenu doit contenir au moins 1 caractère")]
    private ?string $type_contenu = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $date_creation = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $media_url = null;

    #[ORM\OneToMany(mappedBy: 'statut', targetEntity: Commentaire::class, orphanRemoval: true)]
    private Collection $commentaires;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'statuts')]
    #[ORM\JoinTable(
        name: 'favorites',
        joinColumns: [
            new ORM\JoinColumn(name: 'statut_id', referencedColumnName: 'id')
        ],
        inverseJoinColumns: [
            new ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')
        ]
    )]
    private Collection $users;

    #[ORM\OneToMany(mappedBy: 'statut', targetEntity: Reactions::class)]
    private Collection $reactions;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->reactions = new ArrayCollection();
    }

    #[ORM\Column(type: 'boolean')]
    private bool $anonymous = false;

    public function isAnonymous(): bool
    {
        return $this->anonymous;
    }

    public function setAnonymous(bool $anonymous): self
    {
        $this->anonymous = $anonymous;
        return $this;
    }

    // Getters and setters...

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
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

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(?string $contenu): self
    {
        $this->contenu = $contenu;
        return $this;
    }

    public function getTypeContenu(): ?string
    {
        return $this->type_contenu;
    }

    public function setTypeContenu(string $type_contenu): static
    {
        $this->type_contenu = $type_contenu;
        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(?\DateTimeInterface $date_creation): static
    {
        $this->date_creation = $date_creation;
        return $this;
    }

    public function getMediaUrl(): ?string
    {
        return $this->media_url;
    }

    public function setMediaUrl(?string $media_url): static
    {
        $this->media_url = $media_url;
        return $this;
    }

    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setStatut($this);
        }
        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            if ($commentaire->getStatut() === $this) {
                $commentaire->setStatut(null);
            }
        }
        return $this;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }
        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);
        return $this;
    }

    public function getReactions(): Collection
    {
        return $this->reactions;
    }

    public function addReaction(Reactions $reaction): self
    {
        if (!$this->reactions->contains($reaction)) {
            $this->reactions->add($reaction);
            $reaction->setStatut($this);
        }
        return $this;
    }

    public function removeReaction(Reactions $reaction): self
    {
        if ($this->reactions->removeElement($reaction)) {
            if ($reaction->getStatut() === $this) {
                $reaction->setStatut(null);
            }
        }
        return $this;
    }
}