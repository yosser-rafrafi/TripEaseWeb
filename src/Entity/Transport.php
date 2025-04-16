<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TransportRepository;

#[ORM\Entity(repositoryClass: TransportRepository::class)]
#[ORM\Table(name: 'transport')]
class Transport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: 'Le nom du transport est requis.')]
    #[Assert\Length(min: 3, max: 100, minMessage: 'Le nom est trop court.', maxMessage: 'Le nom est trop long.')]
    #[Assert\Regex(pattern: '/^[^0-9]*$/', message: 'Le nom ne doit pas contenir de chiffres.')]
    private ?string $transport_name = null;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: 'La description est requise.')]
    #[Assert\Length(min: 5, max: 255, minMessage: 'La description est trop courte.', maxMessage: 'La description est trop longue.')]
    private ?string $transport_description = null;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: 'L\'emplacement est requis.')]
    private ?string $transport_location = null;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: 'Le type de transport est requis.')]
      private ?string $transport_type = null;


    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $transport_disponibilite = null;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: 'Le pays est requis.')]
    #[Assert\Regex(pattern: '/^[^0-9]*$/', message: 'Le pays ne doit pas contenir de chiffres.')]
    private ?string $transport_pays = null;

    #[ORM\Column(type: 'float', nullable: false)]
    #[Assert\NotBlank(message: 'Le prix est requis.')]
    #[Assert\Type(type: 'float', message: 'Le prix doit être un nombre.')]
    #[Assert\Positive(message: 'Le prix doit être un nombre positif.')]
    private ?float $transport_prix = null;

    #[ORM\Column(type: 'decimal', nullable: true, precision: 8, scale: 6)]
    #[Assert\Range(min: -90, max: 90, notInRangeMessage: 'La latitude doit être entre -90 et 90.')]
    private ?float $latitude = null;

    #[ORM\Column(type: 'decimal', nullable: true, precision: 9, scale: 6)]
    #[Assert\Range(min: -180, max: 180, notInRangeMessage: 'La longitude doit être entre -180 et 180.')]
    private ?float $longitude = null;

    #[ORM\Column(type: 'string', nullable: true)]
    #[Assert\NotBlank(message: 'L\'agence est requise.')]
     private ?string $transport_agence = null;
   

    // Getters and Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getTransportName(): ?string
    {
        return $this->transport_name;
    }

    public function setTransportName(string $transport_name): self
    {
        $this->transport_name = $transport_name;
        return $this;
    }

    public function getTransportDescription(): ?string
    {
        return $this->transport_description;
    }

    public function setTransportDescription(string $transport_description): self
    {
        $this->transport_description = $transport_description;
        return $this;
    }

    public function getTransportLocation(): ?string
    {
        return $this->transport_location;
    }

    public function setTransportLocation(string $transport_location): self
    {
        $this->transport_location = $transport_location;
        return $this;
    }

    public function getTransportType(): ?string
    {
        return $this->transport_type;
    }

    public function setTransportType(string $transport_type): self
    {
        $this->transport_type = $transport_type;
        return $this;
    }

    public function getTransportDisponibilite(): ?string
    {
        return $this->transport_disponibilite;
    }

    public function setTransportDisponibilite(string $transport_disponibilite): self
    {
        $this->transport_disponibilite = $transport_disponibilite;
        return $this;
    }

    public function getTransportPays(): ?string
    {
        return $this->transport_pays;
    }

    public function setTransportPays(string $transport_pays): self
    {
        $this->transport_pays = $transport_pays;
        return $this;
    }

    public function getTransportPrix(): ?float
    {
        return $this->transport_prix;
    }

    public function setTransportPrix(float $transport_prix): self
    {
        $this->transport_prix = $transport_prix;
        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;
        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;
        return $this;
    }
    // Getters and Setters for transport_agence

public function getTransportAgence(): ?string
{
    return $this->transport_agence;
}

public function setTransportAgence(?string $transport_agence): self
{
    $this->transport_agence = $transport_agence;
    return $this;
}

}
