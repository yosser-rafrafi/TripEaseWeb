<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\ReservationtransportRepository;

#[ORM\Entity(repositoryClass: ReservationtransportRepository::class)]
#[ORM\Table(name: 'reservationtransport')]
class Reservationtransport
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

    #[ORM\ManyToOne(targetEntity: Transport::class, inversedBy: 'reservationtransports')]
    #[ORM\JoinColumn(name: 'id_transport', referencedColumnName: 'id')]
    private ?Transport $transport = null;

    public function getTransport(): ?Transport
    {
        return $this->transport;
    }

    public function setTransport(?Transport $transport): self
    {
        $this->transport = $transport;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reservationtransports')]
    #[ORM\JoinColumn(name: 'id_employe', referencedColumnName: 'id')]
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

    #[ORM\Column(type: 'datetime', nullable: false)]
    private ?\DateTimeInterface $date_reservation = null;

    public function getDate_reservation(): ?\DateTimeInterface
    {
        return $this->date_reservation;
    }

    public function setDate_reservation(\DateTimeInterface $date_reservation): self
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

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $notes_reservation = null;

    public function getNotes_reservation(): ?string
    {
        return $this->notes_reservation;
    }

    public function setNotes_reservation(?string $notes_reservation): self
    {
        $this->notes_reservation = $notes_reservation;
        return $this;
    }

    public function getDateReservation(): ?\DateTimeInterface
    {
        return $this->date_reservation;
    }

    public function setDateReservation(\DateTimeInterface $date_reservation): static
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

    public function setNotesReservation(?string $notes_reservation): static
    {
        $this->notes_reservation = $notes_reservation;

        return $this;
    }

}
