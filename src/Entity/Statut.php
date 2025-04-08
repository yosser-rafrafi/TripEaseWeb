<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
<<<<<<< HEAD
=======
use Symfony\Component\Validator\Constraints as Assert;
>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'statuts')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private ?User $user = null;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

<<<<<<< HEAD
    #[ORM\Column(type: 'text', nullable: false)]
    private ?string $type_contenu = null;

=======
>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed
    public function getType_contenu(): ?string
    {
        return $this->type_contenu;
    }

<<<<<<< HEAD
    public function setType_contenu(string $type_contenu): self
=======
    public function setType_contenu(?string $type_contenu): self
>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed
    {
        $this->type_contenu = $type_contenu;
        return $this;
    }

    #[ORM\Column(type: 'text', nullable: false)]
<<<<<<< HEAD
    private ?string $contenu = null;

=======
    #[Assert\NotBlank(message: "Le contenu ne peut pas être vide")]
    #[Assert\Length(
        min: 1,
        minMessage: "Le contenu doit contenir au moins 1 caractère"
    )]
    private ?string $contenu = null;

    #[ORM\Column(type: 'text', nullable: false)]
    #[Assert\NotBlank(message: "Le type de contenu ne peut pas être vide")]
    #[Assert\Length(
        min: 1,
        minMessage: "Le type de contenu doit contenir au moins 1 caractère"
    )]
    private ?string $type_contenu = null;

>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed
    public function getContenu(): ?string
    {
        return $this->contenu;
    }

<<<<<<< HEAD
    public function setContenu(string $contenu): self
=======
    public function setContenu(?string $contenu): self
>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed
    {
        $this->contenu = $contenu;
        return $this;
    }

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $date_creation = null;

    public function getDate_creation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDate_creation(?\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $media_url = null;

    public function getMedia_url(): ?string
    {
        return $this->media_url;
    }

    public function setMedia_url(?string $media_url): self
    {
        $this->media_url = $media_url;
        return $this;
    }

    #[ORM\OneToMany(mappedBy: 'statut', targetEntity: Commentaire::class, orphanRemoval: true)]
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

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        if (!$this->users instanceof Collection) {
            $this->users = new ArrayCollection();
        }
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->getUsers()->contains($user)) {
            $this->getUsers()->add($user);
        }
        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->getUsers()->removeElement($user);
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

    
   
}
