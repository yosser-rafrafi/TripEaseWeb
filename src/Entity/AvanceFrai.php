<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\AvanceFraiRepository;

#[ORM\Entity(repositoryClass: AvanceFraiRepository::class)]
#[ORM\Table(name: 'avance_frais')]
class AvanceFrai
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

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $voyage_id = null;

    public function getVoyage_id(): ?int
    {
        return $this->voyage_id;
    }

    public function setVoyage_id(int $voyage_id): self
    {
        $this->voyage_id = $voyage_id;
        return $this;
    }

    #[ORM\Column(type: 'decimal', nullable: false)]
    private ?float $montant_demande = null;

    public function getMontant_demande(): ?float
    {
        return $this->montant_demande;
    }

    public function setMontant_demande(float $montant_demande): self
    {
        $this->montant_demande = $montant_demande;
        return $this;
    }

    #[ORM\Column(type: 'decimal', nullable: true)]
    private ?float $montant_accorde = null;

    public function getMontant_accorde(): ?float
    {
        return $this->montant_accorde;
    }

    public function setMontant_accorde(?float $montant_accorde): self
    {
        $this->montant_accorde = $montant_accorde;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $devise = null;

    public function getDevise(): ?string
    {
        return $this->devise;
    }

    public function setDevise(string $devise): self
    {
        $this->devise = $devise;
        return $this;
    }

    #[ORM\Column(type: 'text', nullable: false)]
    private ?string $motif = null;

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): self
    {
        $this->motif = $motif;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $type_avance = null;

    public function getType_avance(): ?string
    {
        return $this->type_avance;
    }

    public function setType_avance(string $type_avance): self
    {
        $this->type_avance = $type_avance;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $statut = null;

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $date_demande = null;

    public function getDate_demande(): ?\DateTimeInterface
    {
        return $this->date_demande;
    }

    public function setDate_demande(\DateTimeInterface $date_demande): self
    {
        $this->date_demande = $date_demande;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $date_validation = null;

    public function getDate_validation(): ?\DateTimeInterface
    {
        return $this->date_validation;
    }

    public function setDate_validation(?\DateTimeInterface $date_validation): self
    {
        $this->date_validation = $date_validation;
        return $this;
    }

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $commentaire_manager = null;

    public function getCommentaire_manager(): ?string
    {
        return $this->commentaire_manager;
    }

    public function setCommentaire_manager(?string $commentaire_manager): self
    {
        $this->commentaire_manager = $commentaire_manager;
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

    public function getVoyageId(): ?int
    {
        return $this->voyage_id;
    }

    public function setVoyageId(int $voyage_id): static
    {
        $this->voyage_id = $voyage_id;

        return $this;
    }

    public function getMontantDemande(): ?string
    {
        return $this->montant_demande;
    }

    public function setMontantDemande(string $montant_demande): static
    {
        $this->montant_demande = $montant_demande;

        return $this;
    }

    public function getMontantAccorde(): ?string
    {
        return $this->montant_accorde;
    }

    public function setMontantAccorde(?string $montant_accorde): static
    {
        $this->montant_accorde = $montant_accorde;

        return $this;
    }

    public function getTypeAvance(): ?string
    {
        return $this->type_avance;
    }

    public function setTypeAvance(string $type_avance): static
    {
        $this->type_avance = $type_avance;

        return $this;
    }

    public function getDateDemande(): ?\DateTimeInterface
    {
        return $this->date_demande;
    }

    public function setDateDemande(\DateTimeInterface $date_demande): static
    {
        $this->date_demande = $date_demande;

        return $this;
    }

    public function getDateValidation(): ?\DateTimeInterface
    {
        return $this->date_validation;
    }

    public function setDateValidation(?\DateTimeInterface $date_validation): static
    {
        $this->date_validation = $date_validation;

        return $this;
    }

    public function getCommentaireManager(): ?string
    {
        return $this->commentaire_manager;
    }

    public function setCommentaireManager(?string $commentaire_manager): static
    {
        $this->commentaire_manager = $commentaire_manager;

        return $this;
    }

}
