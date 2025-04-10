<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\AvanceFraiRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AvanceFraiRepository::class)]
#[ORM\Table(name: 'avance_frais')]
class AvanceFrai
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[Assert\NotBlank(message: "L'employé est requis.")]
    #[Assert\Positive(message: "L'identifiant de l'employé doit être positif.")]
    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $employe_id = null;

    #[Assert\NotBlank(message: "Le voyage est requis.")]
    #[Assert\Positive(message: "L'identifiant du voyage doit être positif.")]
    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $voyage_id = 1;

    #[Assert\NotBlank(message: "Le montant demandé est requis.")]
    #[Assert\Positive(message: "Le montant demandé doit être supérieur à zéro.")]
    #[ORM\Column(type: 'decimal', nullable: false)]
    private ?float $montant_demande = null;

    #[Assert\PositiveOrZero(message: "Le montant accordé doit être supérieur ou égal à zéro.")]
    #[ORM\Column(type: 'decimal', nullable: true)]
    private ?float $montant_accorde = null;

    #[Assert\NotBlank(message: "La devise est requise.")]
    #[Assert\Choice(choices: ['TND', 'EUR', 'USD'], message: "La devise doit être TND, EUR ou USD.")]
    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $devise = null;

    #[Assert\NotBlank(message: "Le motif est requis.")]
    #[Assert\Length(min: 5, minMessage: "Le motif doit contenir au moins {{ limit }} caractères.")]
    #[ORM\Column(type: 'text', nullable: false)]
    private ?string $motif = null;

    #[Assert\NotBlank(message: "Le type d'avance est requis.")]
    #[Assert\Choice(choices: ['avance sur frais', 'avance exceptionnelle'], message: "Type d'avance invalide.")]
    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $type_avance = null;

    #[Assert\Choice(choices: ['en attente', 'validée', 'refusée'], message: "Statut invalide.")]
    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $statut = 'en attente';

    #[Assert\NotNull(message: "La date de demande est requise.")]
    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $date_demande = null;

    #[Assert\LessThanOrEqual('today', message: "La date de validation ne peut pas être dans le futur.")]
    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $date_validation = null;

    #[Assert\Length(max: 500, maxMessage: "Le commentaire ne doit pas dépasser {{ limit }} caractères.")]
    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $commentaire_manager = null;

    #[ORM\OneToMany(mappedBy: 'avanceFrai', targetEntity: Frai::class, orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $frais;

    public function __construct()
    {
        $this->date_demande = new \DateTime();
        $this->frais = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function setId(int $id): self { $this->id = $id; return $this; }

    public function getEmployeId(): ?int { return $this->employe_id; }
    public function setEmployeId(int $employe_id): self { $this->employe_id = $employe_id; return $this; }

    public function getVoyageId(): ?int { return $this->voyage_id; }
    public function setVoyageId(int $voyage_id): self { $this->voyage_id = $voyage_id; return $this; }

    public function getMontantDemande(): ?float { return $this->montant_demande; }
    public function setMontantDemande(float $montant_demande): self { $this->montant_demande = $montant_demande; return $this; }

    public function getMontantAccorde(): ?float { return $this->montant_accorde; }
    public function setMontantAccorde(?float $montant_accorde): self { $this->montant_accorde = $montant_accorde; return $this; }

    public function getDevise(): ?string { return $this->devise; }
    public function setDevise(string $devise): self { $this->devise = $devise; return $this; }

    public function getMotif(): ?string { return $this->motif; }
    public function setMotif(string $motif): self { $this->motif = $motif; return $this; }

    public function getTypeAvance(): ?string { return $this->type_avance; }
    public function setTypeAvance(string $type_avance): self { $this->type_avance = $type_avance; return $this; }

    public function getStatut(): ?string { return $this->statut; }
    public function setStatut(string $statut): self { $this->statut = $statut; return $this; }

    public function getDateDemande(): ?\DateTimeInterface { return $this->date_demande; }
    public function setDateDemande(\DateTimeInterface $date_demande): self { $this->date_demande = $date_demande; return $this; }

    public function getDateValidation(): ?\DateTimeInterface { return $this->date_validation; }
    public function setDateValidation(?\DateTimeInterface $date_validation): self { $this->date_validation = $date_validation; return $this; }

    public function getCommentaireManager(): ?string { return $this->commentaire_manager; }
    public function setCommentaireManager(?string $commentaire_manager): self { $this->commentaire_manager = $commentaire_manager; return $this; }

    public function getFrais(): Collection { return $this->frais; }

    public function addFrai(Frai $frais): self
    {
        if (!$this->frais->contains($frais)) {
            $this->frais[] = $frais;
            $frais->setAvanceFrai($this);
        }
        return $this;
    }

    public function removeFrai(Frai $frais): self
    {
        if ($this->frais->removeElement($frais)) {
            if ($frais->getAvanceFrai() === $this) {
                $frais->setAvanceFrai(null);
            }
        }
        return $this;
    }
}
