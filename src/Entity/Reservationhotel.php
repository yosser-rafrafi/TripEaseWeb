<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

use App\Repository\ReservationhotelRepository;

#[ORM\Entity(repositoryClass: ReservationhotelRepository::class)]
#[ORM\Table(name: 'reservationhotel')]
class Reservationhotel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_reservation",type: 'integer')]
    private ?int $id_reservation = null;

    #[ORM\ManyToOne(targetEntity: Hotel::class)]
    #[ORM\JoinColumn(name: 'hotel_id', referencedColumnName: 'id', nullable: false)]

    private ?Hotel $hotel = null;


    #[ORM\ManyToOne(targetEntity: Chambre::class)]
    #[ORM\JoinColumn(name: 'chambre_id', referencedColumnName: 'id_chambre', nullable: false)]
    #[Assert\NotNull(message: "La chambre doit être sélectionnée")]
    private ?Chambre $chambre = null;
    
 
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: true)]
    private ?User $user = null;
    
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $date_reservation = null;
    
    #[ORM\Column(type: 'string', nullable: true)]
    #[Assert\NotBlank(message: "La date de début est obligatoire")]
    #[Assert\Date(message: "La date de début doit être une date valide")]
    
    private ?string $date_debut = null;

    #[ORM\Column(type: 'string', nullable: true)]
    #[Assert\NotBlank(message: "La date de fin est obligatoire")]
    #[Assert\Date(message: "La date de fin doit être une date valide")]
    #[Assert\Expression(
        "this.getDateFin() >= this.getDateDebut()",
        message: "La date de fin doit être postérieure à la date de début"
        )]
        private ?string $date_fin = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $statut = 'Réservé';

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $qrcode = null;

    public function getId_reservation(): ?int
    {
        return $this->id_reservation;
    }

    public function getHotel(): ?Hotel
    {
        return $this->hotel;
    }

    public function setHotel(?Hotel $hotel): self
    {
        $this->hotel = $hotel;
        return $this;
    }

    public function getChambre(): ?Chambre
    {
        return $this->chambre;
    }

    public function setChambre(?Chambre $chambre): self
    {
        $this->chambre = $chambre;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getDateReservation(): ?\DateTime
{
    if ($this->date_reservation) {
        // Tente de convertir la chaîne en DateTime
        $date = \DateTime::createFromFormat('Y-m-d', $this->date_reservation);

        // Vérifie si la conversion a réussi
        if ($date !== false) {
            return $date;
        }
    }
    // Retourne null si la chaîne est vide ou invalide
    return null;
}

    

    public function setDateReservation(?\DateTime $date_reservation): self
    {
        $this->date_reservation = $date_reservation ? $date_reservation->format('Y-m-d') : null;
        return $this;
    }

    public function getDateDebut(): ?\DateTime
    {
        if ($this->date_debut) {
            // Tente de convertir la chaîne en DateTime
            $date = \DateTime::createFromFormat('Y-m-d', $this->date_debut);
    
            // Vérifie si la conversion a réussi
            if ($date !== false) {
                return $date;
            }
        }
        // Retourne null si la chaîne est vide ou invalide
        return null;
    }
    

    public function setDateDebut(?\DateTime  $date_debut): self
    {
        $this->date_debut = $date_debut ? $date_debut->format('Y-m-d') : null ;
        return $this;
    }

    public function getDateFin(): ?\DateTime
    {
        if ($this->date_fin) {
            // Tente de convertir la chaîne en DateTime
            $date = \DateTime::createFromFormat('Y-m-d', $this->date_fin);
    
            // Vérifie si la conversion a réussi
            if ($date !== false) {
                return $date;
            }
        }
        // Retourne null si la chaîne est vide ou invalide
        return null;
    
    }

    public function setDateFin(?\DateTime $date_fin): self
    {
        $this->date_fin = $date_fin? $date_fin->format('Y-m-d') : null ;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

    public function getQrcode(): ?string
    {
        return $this->qrcode;
    }

    public function setQrcode(?string $qrcode): self
    {
        $this->qrcode = $qrcode;
        return $this;
    }
}