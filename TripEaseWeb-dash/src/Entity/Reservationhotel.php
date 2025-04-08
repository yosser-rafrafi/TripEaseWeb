<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
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
    private ?int $id_reservation = null;

    public function getId_reservation(): ?int
    {
        return $this->id_reservation;
    }

    public function setId_reservation(int $id_reservation): self
    {
        $this->id_reservation = $id_reservation;
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
    private ?string $date_reservation = null;

    public function getDate_reservation(): ?string
    {
        return $this->date_reservation;
    }

    public function setDate_reservation(?string $date_reservation): self
    {
        $this->date_reservation = $date_reservation;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $date_debut = null;

    public function getDate_debut(): ?string
    {
        return $this->date_debut;
    }

    public function setDate_debut(?string $date_debut): self
    {
        $this->date_debut = $date_debut;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $date_fin = null;

    public function getDate_fin(): ?string
    {
        return $this->date_fin;
    }

    public function setDate_fin(?string $date_fin): self
    {
        $this->date_fin = $date_fin;
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

    public function getIdReservation(): ?int
    {
        return $this->id_reservation;
    }

    public function getHotelId(): ?int
    {
        return $this->hotel_id;
    }

    public function setHotelId(int $hotel_id): static
    {
        $this->hotel_id = $hotel_id;

        return $this;
    }

    public function getChambreId(): ?int
    {
        return $this->chambre_id;
    }

    public function setChambreId(int $chambre_id): static
    {
        $this->chambre_id = $chambre_id;

        return $this;
    }

    public function getDateReservation(): ?string
    {
        return $this->date_reservation;
    }

    public function setDateReservation(?string $date_reservation): static
    {
        $this->date_reservation = $date_reservation;

        return $this;
    }

    public function getDateDebut(): ?string
    {
        return $this->date_debut;
    }

    public function setDateDebut(?string $date_debut): static
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?string
    {
        return $this->date_fin;
    }

    public function setDateFin(?string $date_fin): static
    {
        $this->date_fin = $date_fin;

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
