<?php

namespace App\Entity;

use App\Repository\AnnonceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnnonceRepository::class)]
class Annonce
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $prix = null;

    #[ORM\ManyToOne(inversedBy: 'annonces')]
    private ?CategorieAnnonce $categorie = null;

    #[ORM\Column(length: 100)]
    private ?string $localisation = null;

    /**
     * @var Collection<int, PhotoAnnonce>
     */
    #[ORM\OneToMany(targetEntity: PhotoAnnonce::class, mappedBy: 'annonce', orphanRemoval: true)]
    private Collection $photoAnnonces;

    #[ORM\ManyToOne(inversedBy: 'annonces')]
    private ?User $user = null;

    public function __construct()
    {
        $this->photoAnnonces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getCategorie(): ?CategorieAnnonce
    {
        return $this->categorie;
    }

    public function setCategorie(?CategorieAnnonce $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): static
    {
        $this->localisation = $localisation;

        return $this;
    }

    /**
     * @return Collection<int, PhotoAnnonce>
     */
    public function getPhotoAnnonces(): Collection
    {
        return $this->photoAnnonces;
    }

    public function addPhotoAnnonce(PhotoAnnonce $photoAnnonce): static
    {
        if (!$this->photoAnnonces->contains($photoAnnonce)) {
            $this->photoAnnonces->add($photoAnnonce);
            $photoAnnonce->setAnnonce($this);
        }

        return $this;
    }

    public function removePhotoAnnonce(PhotoAnnonce $photoAnnonce): static
    {
        if ($this->photoAnnonces->removeElement($photoAnnonce)) {
            // set the owning side to null (unless already changed)
            if ($photoAnnonce->getAnnonce() === $this) {
                $photoAnnonce->setAnnonce(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
