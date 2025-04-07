<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\VoyageUserRepository;

#[ORM\Entity(repositoryClass: VoyageUserRepository::class)]
#[ORM\Table(name: 'voyage_user')]
class VoyageUser
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

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $voyage_id = null;

    public function getVoyage_id(): ?int
    {
        return $this->voyage_id;
    }

    public function setVoyage_id(?int $voyage_id): self
    {
        $this->voyage_id = $voyage_id;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $user_id = null;

    public function getUser_id(): ?int
    {
        return $this->user_id;
    }

    public function setUser_id(?int $user_id): self
    {
        $this->user_id = $user_id;
        return $this;
    }

    public function getVoyageId(): ?int
    {
        return $this->voyage_id;
    }

    public function setVoyageId(?int $voyage_id): static
    {
        $this->voyage_id = $voyage_id;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(?int $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

}
