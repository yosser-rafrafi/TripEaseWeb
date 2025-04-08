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

use App\Repository\CommentaireRepository;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
#[ORM\Table(name: 'commentaire')]
class Commentaire
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

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'commentaires')]
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

    #[ORM\ManyToOne(targetEntity: Statut::class, inversedBy: 'commentaires')]
    #[ORM\JoinColumn(name: 'statut_id', referencedColumnName: 'id')]
    private ?Statut $statut = null;

    public function getStatut(): ?Statut
    {
        return $this->statut;
    }

    public function setStatut(?Statut $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $commentaire_parent_id = null;

    public function getCommentaire_parent_id(): ?int
    {
        return $this->commentaire_parent_id;
    }

    public function setCommentaire_parent_id(?int $commentaire_parent_id): self
    {
        $this->commentaire_parent_id = $commentaire_parent_id;
        return $this;
    }

    #[ORM\Column(type: 'text', nullable: false)]
<<<<<<< HEAD
=======
    #[Assert\NotBlank(message: 'Le contenu ne peut pas être vide.')]
    #[Assert\Length(
        min: 1,
        minMessage: 'Le commentaire doit contenir au moins 1 caractère.'
    )]
>>>>>>> 09b8388c89382e4ec195998d936bfb04cb5d37ed
    private ?string $contenu = null;

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
    private ?\DateTimeInterface $date_publication = null;

    public function getDate_publication(): ?\DateTimeInterface
    {
        return $this->date_publication;
    }

    public function setDate_publication(?\DateTimeInterface $date_publication): self
    {
        $this->date_publication = $date_publication;
        return $this;
    }

    #[ORM\ManyToMany(targetEntity: Statut::class, inversedBy: 'commentaires')]
    #[ORM\JoinTable(
        name: 'reactions',
        joinColumns: [
            new ORM\JoinColumn(name: 'commentaire_id', referencedColumnName: 'id')
        ],
        inverseJoinColumns: [
            new ORM\JoinColumn(name: 'statut_id', referencedColumnName: 'id')
        ]
    )]
    private Collection $statuts;

    public function __construct()
    {
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

    public function getCommentaireParentId(): ?int
    {
        return $this->commentaire_parent_id;
    }

    public function setCommentaireParentId(?int $commentaire_parent_id): static
    {
        $this->commentaire_parent_id = $commentaire_parent_id;

        return $this;
    }

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->date_publication;
    }

    public function setDatePublication(?\DateTimeInterface $date_publication): static
    {
        $this->date_publication = $date_publication;

        return $this;
    }

}
