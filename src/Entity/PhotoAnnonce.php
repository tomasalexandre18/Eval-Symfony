<?php

namespace App\Entity;

use App\Repository\PhotoAnnonceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PhotoAnnonceRepository::class)]
class PhotoAnnonce
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'photoAnnonces')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Annonce $annonce = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\PostRemove]
    public function postRemoveFile() {
        $path = "/public/$this->path";
        if (!unlink($path)) {
            trigger_error("FILE NOT DELETED", E_USER_WARNING);
        };
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnnonce(): ?Annonce
    {
        return $this->annonce;
    }

    public function setAnnonce(?Annonce $annonce): static
    {
        $this->annonce = $annonce;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }
}
