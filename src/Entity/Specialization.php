<?php

namespace App\Entity;

use App\Repository\SpecializationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SpecializationRepository::class)
 */
class Specialization
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Dietetician::class, mappedBy="specializations")
     */
    private $dietetician;

    public function __construct()
    {
        $this->dietetician = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Dietetician[]
     */
    public function getDietetician(): Collection
    {
        return $this->dietetician;
    }

    public function addDietetician(Dietetician $dietetician): self
    {
        if (!$this->dietetician->contains($dietetician)) {
            $this->dietetician[] = $dietetician;
        }

        return $this;
    }

    public function removeDietetician(Dietetician $dietetician): self
    {
        $this->dietetician->removeElement($dietetician);

        return $this;
    }
}
