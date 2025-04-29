<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReservationtransportRepository;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as CustomAssert;

#[ORM\Entity(repositoryClass: ReservationtransportRepository::class)]
#[ORM\Table(name: 'reservationtransport')]


class Reservationtransport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;
    
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'id_employe', referencedColumnName: 'id', nullable: false, onDelete: 'RESTRICT')]
    #[Assert\NotNull(message: "L'employé est requis.")]
    private ?User $employe = null;
    
    #[ORM\ManyToOne(targetEntity: Transport::class)]
    #[ORM\JoinColumn(name: 'id_transport', referencedColumnName: 'id', nullable: false, onDelete: 'RESTRICT')]
    #[Assert\NotNull(message: "Le transport est requis.")]
    private ?Transport $transport = null;

  
    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Assert\Type(type: \DateTimeInterface::class, message: "Le format de la date est invalide.")]
    #[Assert\GreaterThan("today", message: "La date de réservation doit être dans le futur.")]
    private ?\DateTimeInterface $date_reservation_fin = null;
    
    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Assert\Type(type: \DateTimeInterface::class, message: "Le format de la date est invalide.")]
    #[Assert\GreaterThan("today", message: "La date de réservation doit être dans le futur.")]
    private ?\DateTimeInterface $date_reservation = null;

    #[ORM\Column(type: 'string', length: 20, nullable: false)]
    #[Assert\NotBlank(message: "Le type de réservation est requis.")]
    #[Assert\Length(
        min: 3,
        max: 20,
        minMessage: "Le type de réservation est trop court (min 3 caractères).",
        maxMessage: "Le type de réservation est trop long (max 20 caractères)."
    )]
    private ?string $type_reservation = null;
    
    #[ORM\Column(type: 'string', length: 20, nullable: false)]
    #[Assert\NotBlank(message: "La priorité est requise.")]
    #[Assert\Choice(
        choices: ['High', 'Medium', 'Low'],
        message: "La priorité doit être 'High', 'Medium' ou 'Low'."
    )]
    private ?string $priorite_reservation = null;
    
    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\Length(
        max: 255,
        maxMessage: "Les notes ne peuvent pas dépasser 255 caractères."
    )]
    private ?string $notes_reservation = null;
    

    // Getters and Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmploye(): ?User
    {
        return $this->employe;
    }

    public function setEmploye(User $employe): static
    {
        $this->employe = $employe;
        return $this;
    }

    public function getTransport(): ?Transport
    {
        return $this->transport;
    }

    public function setTransport(Transport $transport): static
    {
        $this->transport = $transport;
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

    public function getDateReservationFin(): ?\DateTimeInterface
    {
        return $this->date_reservation_fin;
    }
    
    public function setDateReservationFin(?\DateTimeInterface $date_reservation_fin): self
    {
        $this->date_reservation_fin = $date_reservation_fin;
    
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
