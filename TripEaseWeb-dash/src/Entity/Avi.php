<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\AviRepository;

#[ORM\Entity(repositoryClass: AviRepository::class)]
#[ORM\Table(name: 'avis')]
class Avi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $idAvis = null;

    public function getIdAvis(): ?int
    {
        return $this->idAvis;
    }

    public function setIdAvis(int $idAvis): self
    {
        $this->idAvis = $idAvis;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $idUser = null;

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function setIdUser(int $idUser): self
    {
        $this->idUser = $idUser;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $idHotel = null;

    public function getIdHotel(): ?int
    {
        return $this->idHotel;
    }

    public function setIdHotel(int $idHotel): self
    {
        $this->idHotel = $idHotel;
        return $this;
    }

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $commentaire = null;

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $note = null;

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): self
    {
        $this->note = $note;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $dateAvis = null;

    public function getDateAvis(): ?\DateTimeInterface
    {
        return $this->dateAvis;
    }

    public function setDateAvis(\DateTimeInterface $dateAvis): self
    {
        $this->dateAvis = $dateAvis;
        return $this;
    }

}
