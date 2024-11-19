<?php

namespace App\Entity;

use App\Repository\GalerieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GalerieRepository::class)]
class Galerie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $publiee = null;

    #[ORM\ManyToOne(inversedBy: 'galeries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Member $creator = null;

    /**
     * @var Collection<int, Watch>
     */
    #[ORM\ManyToMany(targetEntity: Watch::class, inversedBy: 'galeries')]
    private Collection $watchs;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    public function __construct()
    {
        $this->watchs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function isPubliee(): ?bool
    {
        return $this->publiee;
    }

    public function setPubliee(bool $publiee): static
    {
        $this->publiee = $publiee;

        return $this;
    }

    public function getCreator(): ?Member
    {
        return $this->creator;
    }

    public function setCreator(?Member $creator): static
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * @return Collection<int, Watch>
     */
    public function getWatchs(): Collection
    {
        return $this->watchs;
    }

    public function addWatch(Watch $watch): static
    {
        if (!$this->watchs->contains($watch)) {
            $this->watchs->add($watch);
        }

        return $this;
    }

    public function removeWatch(Watch $watch): static
    {
        $this->watchs->removeElement($watch);

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
