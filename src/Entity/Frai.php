<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\FraiRepository;
use Symfony\Component\Validator\Constraints as Assert;

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

    #[ORM\ManyToOne(targetEntity: AvanceFrai::class)]
    #[ORM\JoinColumn(name: "id_avance_frais", referencedColumnName: "id", nullable: false)]
    private ?AvanceFrai $avanceFrai = null;

// Getter/Setter :
public function getAvanceFrai(): ?AvanceFrai
{
    return $this->avanceFrai;
}

public function setAvanceFrai(AvanceFrai $avanceFrai): self
{
    $this->avanceFrai = $avanceFrai;
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
    #[Assert\NotBlank(message: "Le type de dépense est requis.")]
    #[Assert\Length(max: 20, maxMessage: "Le type ne peut pas dépasser {{ limit }} caractères.")]
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
    #[Assert\NotBlank(message: "Le montant est requis.")]
    #[Assert\PositiveOrZero(message: "Le montant doit être supérieur ou égal à 0.")]
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
    #[Assert\NotBlank(message: "La date de dépense est requise.")]
    #[Assert\Type(\DateTimeInterface::class)]
    #[Assert\LessThanOrEqual('today', message: "La date ne peut pas être dans le futur.")]
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
    private $pdf; 
    
    
    public function getPdf()
    {
        return $this->pdf;
    }
    
    public function setPdf($pdf): self
    {
        $this->pdf = $pdf;
        return $this;
    }
    

    public function getIdAvanceFrais(): ?int
    {
        return $this->id_avance_frais;
    }

    public function setIdAvanceFrais(int $id_avance_frais): static
    {
        $this->id_avance_frais = $id_avance_frais;

        return $this;
    }

    public function getEmployeId(): ?int
    {
        return $this->employe_id;
    }

    public function setEmployeId(int $employe_id): static
    {
        $this->employe_id = $employe_id;

        return $this;
    }

    public function getDateDepense(): ?\DateTimeInterface
    {
        return $this->date_depense;
    }

    public function setDateDepense(\DateTimeInterface $date_depense): static
    {
        $this->date_depense = $date_depense;

        return $this;
    }

}
