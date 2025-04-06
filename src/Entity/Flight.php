<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\FlightRepository;

#[ORM\Entity(repositoryClass: FlightRepository::class)]
#[ORM\Table(name: 'flight')]
class Flight
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

    #[ORM\ManyToOne(targetEntity: Voyage::class, inversedBy: 'flights')]
    #[ORM\JoinColumn(name: 'voyage_id', referencedColumnName: 'id')]
    private ?Voyage $voyage = null;

    public function getVoyage(): ?Voyage
    {
        return $this->voyage;
    }

    public function setVoyage(?Voyage $voyage): static
    {
        $this->voyage = $voyage;

        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $flight_number = null;

    public function getFlight_number(): ?string
    {
        return $this->flight_number;
    }

    public function setFlight_number(string $flight_number): self
    {
        $this->flight_number = $flight_number;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $airline = null;

    public function getAirline(): ?string
    {
        return $this->airline;
    }

    public function setAirline(string $airline): static
    {
        $this->airline = $airline;

        return $this;
    }

    #[ORM\Column(type: 'datetime', nullable: false)]
    private ?\DateTimeInterface $departure_datetime = null;

    public function getDeparture_datetime(): ?\DateTimeInterface
    {
        return $this->departure_datetime;
    }

    public function setDeparture_datetime(\DateTimeInterface $departure_datetime): self
    {
        $this->departure_datetime = $departure_datetime;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $departure_airport = null;

    public function getDeparture_airport(): ?string
    {
        return $this->departure_airport;
    }

    public function setDeparture_airport(string $departure_airport): self
    {
        $this->departure_airport = $departure_airport;
        return $this;
    }

    #[ORM\Column(type: 'datetime', nullable: false)]
    private ?\DateTimeInterface $arrival_datetime = null;

    public function getArrival_datetime(): ?\DateTimeInterface
    {
        return $this->arrival_datetime;
    }

    public function setArrival_datetime(\DateTimeInterface $arrival_datetime): self
    {
        $this->arrival_datetime = $arrival_datetime;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $arrival_airport = null;

    public function getArrival_airport(): ?string
    {
        return $this->arrival_airport;
    }

    public function setArrival_airport(string $arrival_airport): self
    {
        $this->arrival_airport = $arrival_airport;
        return $this;
    }

    public function getFlightNumber(): ?string
    {
        return $this->flight_number;
    }

    public function setFlightNumber(string $flight_number): static
    {
        $this->flight_number = $flight_number;

        return $this;
    }

    public function getDepartureDatetime(): ?\DateTimeInterface
    {
        return $this->departure_datetime;
    }

    public function setDepartureDatetime(\DateTimeInterface $departure_datetime): static
    {
        $this->departure_datetime = $departure_datetime;

        return $this;
    }

    public function getDepartureAirport(): ?string
    {
        return $this->departure_airport;
    }

    public function setDepartureAirport(string $departure_airport): static
    {
        $this->departure_airport = $departure_airport;

        return $this;
    }

    public function getArrivalDatetime(): ?\DateTimeInterface
    {
        return $this->arrival_datetime;
    }

    public function setArrivalDatetime(\DateTimeInterface $arrival_datetime): static
    {
        $this->arrival_datetime = $arrival_datetime;

        return $this;
    }

    public function getArrivalAirport(): ?string
    {
        return $this->arrival_airport;
    }

    public function setArrivalAirport(string $arrival_airport): static
    {
        $this->arrival_airport = $arrival_airport;

        return $this;
    }

}
