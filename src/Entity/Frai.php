<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\FraiRepository;

#[ORM\Entity(repositoryClass: FraiRepository::class)]
#[ORM\Table(name: 'frais')]
class Frai
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
    private ?int $id_avance_frais = null;

    public function getId_avance_frais(): ?int
    {
        return $this->id_avance_frais;
    }

    public function setId_avance_frais(int $id_avance_frais): self
    {
        $this->id_avance_frais = $id_avance_frais;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $employe_id = null;

    public function getEmploye_id(): ?int
    {
        return $this->employe_id;
    }

    public function setEmploye_id(int $employe_id): self
    {
        $this->employe_id = $employe_id;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $type = null;

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    #[ORM\Column(type: 'decimal', nullable: false)]
    private ?float $montant = null;

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $date_depense = null;

    public function getDate_depense(): ?\DateTimeInterface
    {
        return $this->date_depense;
    }

    public function setDate_depense(\DateTimeInterface $date_depense): self
    {
        $this->date_depense = $date_depense;
        return $this;
    }

    #[ORM\Column(type: 'blob', nullable: false)]
    private ?string $pdf = null;

    public function getPdf(): ?string
    {
        return $this->pdf;
    }

    public function setPdf(string $pdf): self
    {
        $this->pdf = $pdf;
        return $this;
    }

}
