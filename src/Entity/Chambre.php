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

    #[ORM\Column(type: 'decimal', nullable: false)]
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

}
