<?php

namespace App\Entity;

use App\Repository\RackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RackRepository::class)]
class Rack
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Watch>
     */
    #[ORM\OneToMany(targetEntity: Watch::class, mappedBy: 'rack', orphanRemoval: true)]
    private Collection $watchs;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;
    
    #[ORM\OneToOne(mappedBy: 'rack', cascade: ['persist', 'remove'])]
    private ?Member $member = null;

    public function __construct()
    {
        $this->watchs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $watch->setRack($this);
        }

        return $this;
    }

    public function removeWatch(Watch $watch): static
    {
        if ($this->watchs->removeElement($watch)) {
            // set the owning side to null (unless already changed)
            if ($watch->getRack() === $this) {
                $watch->setRack(null);
            }
        }

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }
    
    public function getMember(): ?Member
    {
        return $this->member;
    }    
    public function setMember(?Member $member): self
    {
        $this->member = $member;
        return $this;
    }
}
