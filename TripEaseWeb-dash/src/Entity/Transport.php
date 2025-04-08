<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\TransportRepository;

#[ORM\Entity(repositoryClass: TransportRepository::class)]
#[ORM\Table(name: 'transport')]
class Transport
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

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $transport_name = null;

    public function getTransport_name(): ?string
    {
        return $this->transport_name;
    }

    public function setTransport_name(string $transport_name): self
    {
        $this->transport_name = $transport_name;
        return $this;
    }

    #[ORM\Column(type: 'text', nullable: false)]
    private ?string $transport_description = null;

    public function getTransport_description(): ?string
    {
        return $this->transport_description;
    }

    public function setTransport_description(string $transport_description): self
    {
        $this->transport_description = $transport_description;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $transport_location = null;

    public function getTransport_location(): ?string
    {
        return $this->transport_location;
    }

    public function setTransport_location(string $transport_location): self
    {
        $this->transport_location = $transport_location;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $transport_type = null;

    public function getTransport_type(): ?string
    {
        return $this->transport_type;
    }

    public function setTransport_type(string $transport_type): self
    {
        $this->transport_type = $transport_type;
        return $this;
    }

    #[ORM\Column(type: 'boolean', nullable: false)]
    private ?bool $transport_disponibilite = null;

    public function isTransport_disponibilite(): ?bool
    {
        return $this->transport_disponibilite;
    }

    public function setTransport_disponibilite(bool $transport_disponibilite): self
    {
        $this->transport_disponibilite = $transport_disponibilite;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $transport_pays = null;

    public function getTransport_pays(): ?string
    {
        return $this->transport_pays;
    }

    public function setTransport_pays(string $transport_pays): self
    {
        $this->transport_pays = $transport_pays;
        return $this;
    }

    #[ORM\Column(type: 'decimal', nullable: false)]
    private ?float $transport_prix = null;

    public function getTransport_prix(): ?float
    {
        return $this->transport_prix;
    }

    public function setTransport_prix(float $transport_prix): self
    {
        $this->transport_prix = $transport_prix;
        return $this;
    }

    #[ORM\Column(type: 'decimal', nullable: true)]
    private ?float $latitude = null;

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;
        return $this;
    }

    #[ORM\Column(type: 'decimal', nullable: true)]
    private ?float $longitude = null;

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;
        return $this;
    }

    #[ORM\OneToMany(targetEntity: Reservationtransport::class, mappedBy: 'transport')]
    private Collection $reservationtransports;

    public function __construct()
    {
        $this->reservationtransports = new ArrayCollection();
    }

    /**
     * @return Collection<int, Reservationtransport>
     */
    public function getReservationtransports(): Collection
    {
        if (!$this->reservationtransports instanceof Collection) {
            $this->reservationtransports = new ArrayCollection();
        }
        return $this->reservationtransports;
    }

    public function addReservationtransport(Reservationtransport $reservationtransport): self
    {
        if (!$this->getReservationtransports()->contains($reservationtransport)) {
            $this->getReservationtransports()->add($reservationtransport);
        }
        return $this;
    }

    public function removeReservationtransport(Reservationtransport $reservationtransport): self
    {
        $this->getReservationtransports()->removeElement($reservationtransport);
        return $this;
    }

    public function getTransportName(): ?string
    {
        return $this->transport_name;
    }

    public function setTransportName(string $transport_name): static
    {
        $this->transport_name = $transport_name;

        return $this;
    }

    public function getTransportDescription(): ?string
    {
        return $this->transport_description;
    }

    public function setTransportDescription(string $transport_description): static
    {
        $this->transport_description = $transport_description;

        return $this;
    }

    public function getTransportLocation(): ?string
    {
        return $this->transport_location;
    }

    public function setTransportLocation(string $transport_location): static
    {
        $this->transport_location = $transport_location;

        return $this;
    }

    public function getTransportType(): ?string
    {
        return $this->transport_type;
    }

    public function setTransportType(string $transport_type): static
    {
        $this->transport_type = $transport_type;

        return $this;
    }

    public function isTransportDisponibilite(): ?bool
    {
        return $this->transport_disponibilite;
    }

    public function setTransportDisponibilite(bool $transport_disponibilite): static
    {
        $this->transport_disponibilite = $transport_disponibilite;

        return $this;
    }

    public function getTransportPays(): ?string
    {
        return $this->transport_pays;
    }

    public function setTransportPays(string $transport_pays): static
    {
        $this->transport_pays = $transport_pays;

        return $this;
    }

    public function getTransportPrix(): ?string
    {
        return $this->transport_prix;
    }

    public function setTransportPrix(string $transport_prix): static
    {
        $this->transport_prix = $transport_prix;

        return $this;
    }

}
