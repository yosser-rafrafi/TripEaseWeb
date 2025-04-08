<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReservationtransportRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReservationtransportRepository::class)]
#[ORM\Table(name: 'reservationtransport')]
class Reservationtransport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    // Foreign Key to Employee (User)
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'id_employe', referencedColumnName: 'id', nullable: false, onDelete: 'RESTRICT')]
    #[Assert\NotNull(message: "Employee is required.")]
    private ?User $employe = null;

    // Foreign Key to Transport
    #[ORM\ManyToOne(targetEntity: Transport::class)]
    #[ORM\JoinColumn(name: 'id_transport', referencedColumnName: 'id', nullable: false, onDelete: 'RESTRICT')]
    #[Assert\NotNull(message: "Transport is required.")]
    private ?Transport $transport = null;

    #[ORM\Column(type: 'datetime', nullable: false)]
    #[Assert\NotNull(message: "Reservation date is required.")]
    #[Assert\Type(\DateTimeInterface::class, message: "Invalid date format.")]
    private ?\DateTimeInterface $date_reservation = null;

    #[ORM\Column(type: 'string', length: 20, nullable: false)]
    #[Assert\NotBlank(message: "Reservation type is required.")]
    #[Assert\Length(min: 3, max: 20)]
    private ?string $type_reservation = null;

    #[ORM\Column(type: 'string', length: 20, nullable: false)]
    #[Assert\NotBlank(message: "Reservation priority is required.")]
    #[Assert\Choice(choices: ['High', 'Medium', 'Low'], message: "Priority must be High, Medium, or Low.")]
    private ?string $priorite_reservation = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\Length(max: 255, message: "Notes cannot exceed 255 characters.")]
    private ?string $notes_reservation = null;

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
