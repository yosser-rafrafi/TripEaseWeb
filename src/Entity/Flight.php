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

    #[ORM\OneToOne(inversedBy: 'flight', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'voyage_id', referencedColumnName: 'id', nullable: false)]
    private ?Voyage $voyage = null;

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $flight_number = null;

   
    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $airline = null;


    #[ORM\Column(type: 'datetime', nullable: false)]
    private ?\DateTimeInterface $departure_datetime = null;


    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $departure_airport = null;

    
    #[ORM\Column(type: 'datetime', nullable: false)]
    private ?\DateTimeInterface $arrival_datetime = null;

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $arrival_airport = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
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

    public function getArrivalAirport(): ?string
    {
        return $this->arrival_airport;
    }

    public function setArrivalAirport(string $arrival_airport): static
    {
        $this->arrival_airport = $arrival_airport;

        return $this;
    }

    
    public function getDepartureDatetime(): ?\DateTimeInterface
    {
        return $this->departure_datetime;
    }

    public function setDepartureDatetime(\DateTimeInterface $departure_datetime): self
    {
        $this->departure_datetime = $departure_datetime;
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

    public function getAirline(): ?string
    {
        return $this->airline;
    }

    public function setAirline(string $airline): self
    {
        $this->airline = $airline;
        return $this;
    }

    public function getFlight_number(): ?string
    {
        return $this->flight_number;
    }

    public function setFlight_number(string $flight_number): self
    {
        $this->flight_number = $flight_number;
        return $this;
    }
    

    public function getVoyage(): ?Voyage
    {
        return $this->voyage;
    }
    
    public function setVoyage(?Voyage $voyage): self
    {
        $this->voyage = $voyage;
        return $this;
    }
    

   

   

}
