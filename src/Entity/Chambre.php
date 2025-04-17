<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\ChambreRepository;

#[ORM\Entity(repositoryClass: ChambreRepository::class)]
#[ORM\Table(name: 'chambre')]
class Chambre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_chambre = null;

    public function getId_chambre(): ?int
    {
        return $this->id_chambre;
    }

    public function setId_chambre(int $id_chambre): self
    {
        $this->id_chambre = $id_chambre;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: Hotel::class, inversedBy: 'chambres')]
    #[ORM\JoinColumn(name: 'hotel_id', referencedColumnName: 'id')]
    private ?Hotel $hotel = null;

    public function getHotel(): ?Hotel
    {
        return $this->hotel;
    }

    public function setHotel(?Hotel $hotel): self
    {
        $this->hotel = $hotel;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $type_chambre = null;

    public function getType_chambre(): ?string
    {
        return $this->type_chambre;
    }

    public function setType_chambre(string $type_chambre): self
    {
        $this->type_chambre = $type_chambre;
        return $this;
    }

    #[ORM\Column(type: 'float', nullable: false)]
    private ?float $prix_par_nuit = null;

    public function getPrix_par_nuit(): ?float
    {
        return $this->prix_par_nuit;
    }

    public function setPrix_par_nuit(float $prix_par_nuit): self
    {
        $this->prix_par_nuit = $prix_par_nuit;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $disponibilite = null;

    public function getDisponibilite(): ?int
    {
        return $this->disponibilite;
    }

    public function setDisponibilite(int $disponibilite): self
    {
        $this->disponibilite = $disponibilite;
        return $this;
    }

    public function getIdChambre(): ?int
    {
        return $this->id_chambre;
    }

    public function getTypeChambre(): ?string
    {
        return $this->type_chambre;
    }

    public function setTypeChambre(string $type_chambre): static
    {
        $this->type_chambre = $type_chambre;

        return $this;
    }

    public function getPrixParNuit(): ?float
    {
        return $this->prix_par_nuit;
    }

    public function setPrixParNuit(float $prix_par_nuit): static
    {
        $this->prix_par_nuit = $prix_par_nuit;

        return $this;
    }

}
