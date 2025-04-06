<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\ReservationTransportRepository;

#[ORM\Entity(repositoryClass: ReservationTransportRepository::class)]
#[ORM\Table(name: 'reservation_transport')]
class ReservationTransport
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

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $id_employe = null;

    public function getId_employe(): ?int
    {
        return $this->id_employe;
    }

    public function setId_employe(int $id_employe): self
    {
        $this->id_employe = $id_employe;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $id_transport = null;

    public function getId_transport(): ?int
    {
        return $this->id_transport;
    }

    public function setId_transport(int $id_transport): self
    {
        $this->id_transport = $id_transport;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $date_reservation = null;

    public function getDate_reservation(): ?string
    {
        return $this->date_reservation;
    }

    public function setDate_reservation(string $date_reservation): self
    {
        $this->date_reservation = $date_reservation;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $type_reservation = null;

    public function getType_reservation(): ?string
    {
        return $this->type_reservation;
    }

    public function setType_reservation(string $type_reservation): self
    {
        $this->type_reservation = $type_reservation;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $priorite_reservation = null;

    public function getPriorite_reservation(): ?string
    {
        return $this->priorite_reservation;
    }

    public function setPriorite_reservation(string $priorite_reservation): self
    {
        $this->priorite_reservation = $priorite_reservation;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $notes_reservation = null;

    public function getNotes_reservation(): ?string
    {
        return $this->notes_reservation;
    }

    public function setNotes_reservation(string $notes_reservation): self
    {
        $this->notes_reservation = $notes_reservation;
        return $this;
    }

    public function getIdEmploye(): ?int
    {
        return $this->id_employe;
    }

    public function setIdEmploye(int $id_employe): static
    {
        $this->id_employe = $id_employe;

        return $this;
    }

    public function getIdTransport(): ?int
    {
        return $this->id_transport;
    }

    public function setIdTransport(int $id_transport): static
    {
        $this->id_transport = $id_transport;

        return $this;
    }

    public function getDateReservation(): ?string
    {
        return $this->date_reservation;
    }

    public function setDateReservation(string $date_reservation): static
    {
        $this->date_reservation = $date_reservation;

        return $this;
    }

    public function getTypeReservation(): ?string
    {
        return $this->type_reservation;
    }

    public function setTypeReservation(string $type_reservation): static
    {
        $this->type_reservation = $type_reservation;

        return $this;
    }

    public function getPrioriteReservation(): ?string
    {
        return $this->priorite_reservation;
    }

    public function setPrioriteReservation(string $priorite_reservation): static
    {
        $this->priorite_reservation = $priorite_reservation;

        return $this;
    }

    public function getNotesReservation(): ?string
    {
        return $this->notes_reservation;
    }

    public function setNotesReservation(string $notes_reservation): static
    {
        $this->notes_reservation = $notes_reservation;

        return $this;
    }

}
