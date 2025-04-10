<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

use App\Repository\HotelRepository;

#[ORM\Entity(repositoryClass: HotelRepository::class)]
#[ORM\Table(name: 'hotel')]
class Hotel
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
    #[Assert\NotBlank(message: "Le nom de l'hôtel est requis.")]
    #[Assert\Length(min: 3, max: 100, minMessage: "Le nom est trop court.", maxMessage: "Le nom est trop long.")]
    private ?string $nom = null;

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: "L'adresse est requise.")]
    private ?string $adresse = null;

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: "La ville est requise.")]

    private ?string $ville = null;

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: "Le pays est requis.")]

    private ?string $pays = null;

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): self
    {
        $this->pays = $pays;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    #[Assert\NotNull(message: "Le nombre d'étoiles est requis.")]
    #[Assert\Range(
    min: 1,
    max: 5,
    notInRangeMessage: "Le nombre d'étoiles doit être entre {{ min }} et {{ max }}."
)]
    private ?int $nombre_etoiles = null;

    public function getNombre_etoiles(): ?int
    {
        return $this->nombre_etoiles;
    }

    public function setNombre_etoiles(int $nombre_etoiles): self
    {
        $this->nombre_etoiles = $nombre_etoiles;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: "Le numéro de téléphone est requis.")]
#[Assert\Regex(
    pattern: "/^\+?[0-9\s\-]{8,20}$/",
    message: "Le numéro de téléphone n'est pas valide."
    )]
    private ?string $telephone = null;

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: "L'adresse email est requise.")]
#[Assert\Email(message: "L'adresse email '{{ value }}' n'est pas valide.")]
    private ?string $email = null;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $site_web = null;

    public function getSite_web(): ?string
    {
        return $this->site_web;
    }

    public function setSite_web(?string $site_web): self
    {
        $this->site_web = $site_web;
        return $this;
    }

    #[ORM\OneToMany(targetEntity: Chambre::class, mappedBy: 'hotel')]
    private Collection $chambres;

    public function __construct()
    {
        $this->chambres = new ArrayCollection();
    }

    /**
     * @return Collection<int, Chambre>
     */
    public function getChambres(): Collection
    {
        if (!$this->chambres instanceof Collection) {
            $this->chambres = new ArrayCollection();
        }
        return $this->chambres;
    }

    public function addChambre(Chambre $chambre): self
    {
        if (!$this->getChambres()->contains($chambre)) {
            $this->getChambres()->add($chambre);
        }
        return $this;
    }

    public function removeChambre(Chambre $chambre): self
    {
        $this->getChambres()->removeElement($chambre);
        return $this;
    }

    public function getNombreEtoiles(): ?int
    {
        return $this->nombre_etoiles;
    }

    public function setNombreEtoiles(int $nombre_etoiles): static
    {
        $this->nombre_etoiles = $nombre_etoiles;

        return $this;
    }

    public function getSiteWeb(): ?string
    {
        return $this->site_web;
    }

    public function setSiteWeb(?string $site_web): static
    {
        $this->site_web = $site_web;

        return $this;
    }

}
