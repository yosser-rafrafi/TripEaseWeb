<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\ReservationhotelRepository;

#[ORM\Entity(repositoryClass: ReservationhotelRepository::class)]
#[ORM\Table(name: 'reservationhotel')]
class Reservationhotel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $idReservation = null;

    public function getIdReservation(): ?int
    {
        return $this->idReservation;
    }

    public function setIdReservation(int $idReservation): self
    {
        $this->idReservation = $idReservation;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $hotel_id = null;

    public function getHotel_id(): ?int
    {
        return $this->hotel_id;
    }

    public function setHotel_id(int $hotel_id): self
    {
        $this->hotel_id = $hotel_id;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $chambre_id = null;

    public function getChambre_id(): ?int
    {
        return $this->chambre_id;
    }

    public function setChambre_id(int $chambre_id): self
    {
        $this->chambre_id = $chambre_id;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $dateReservation = null;

    public function getDateReservation(): ?string
    {
        return $this->dateReservation;
    }

    public function setDateReservation(?string $dateReservation): self
    {
        $this->dateReservation = $dateReservation;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $dateDebut = null;

    public function getDateDebut(): ?string
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?string $dateDebut): self
    {
        $this->dateDebut = $dateDebut;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $dateFin = null;

    public function getDateFin(): ?string
    {
        return $this->dateFin;
    }

    public function setDateFin(?string $dateFin): self
    {
        $this->dateFin = $dateFin;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $statut = null;

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): self
    {
        $this->statut = $statut;
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

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $qrcode = null;

    public function getQrcode(): ?string
    {
        return $this->qrcode;
    }

    public function setQrcode(?string $qrcode): self
    {
        $this->qrcode = $qrcode;
        return $this;
    }

}
