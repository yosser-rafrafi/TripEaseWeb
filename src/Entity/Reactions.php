<?php

namespace App\Entity;

use App\Repository\ReactionsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReactionsRepository::class)]
#[ORM\Table(name: 'reactions')]
class Reactions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: true)]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Statut::class, inversedBy: 'reactions')]
    #[ORM\JoinColumn(name: 'statut_id', referencedColumnName: 'id', nullable: true, onDelete: 'CASCADE')]
    private ?Statut $statut = null;

    #[ORM\ManyToOne(targetEntity: Commentaire::class)]
    #[ORM\JoinColumn(name: 'commentaire_id', referencedColumnName: 'id', nullable: true)]
    private ?Commentaire $commentaire = null;

    #[ORM\Column(type: 'string', length: 10, nullable: true)]
    #[Assert\Choice(choices: ['LIKE', 'DISLIKE'], message: 'Type must be LIKE or DISLIKE')]
    private ?string $type = null;

    #[ORM\Column(name: 'creation_date', type: 'datetime', nullable: true, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $creationDate = null;

    // === Getters and Setters ===

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStatut(): ?Statut
    {
        return $this->statut;
    }

    public function setStatut(?Statut $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

    public function getCommentaire(): ?Commentaire
    {
        return $this->commentaire;
    }

    public function setCommentaire(?Commentaire $commentaire): self
    {
        $this->commentaire = $commentaire;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(?\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;
        return $this;
    }
}
