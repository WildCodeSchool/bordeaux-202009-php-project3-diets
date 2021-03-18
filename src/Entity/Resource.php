<?php

namespace App\Entity;

use App\Repository\ResourceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=ResourceRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Resource
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $link;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="ressources")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity=Pathology::class, inversedBy="ressources")
     */
    private $pathology;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=ResourceFormat::class, inversedBy="ressources")
     * @ORM\JoinColumn(nullable=false)
     */
    private $resourceFormat;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Price;

    /**
     * @ORM\OneToMany(targetEntity=ResourceFile::class, mappedBy="resource", cascade={"persist", "remove"})
     */
    private $resourceFiles;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_start;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_end;

    public function __construct()
    {
        $this->pathology = new ArrayCollection();
        $this->resourceFiles = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

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

    /**
     * @return Collection|Pathology[]
     */
    public function getPathology(): Collection
    {
        return $this->pathology;
    }

    public function addPathology(Pathology $pathology): self
    {
        if (!$this->pathology->contains($pathology)) {
            $this->pathology[] = $pathology;
        }

        return $this;
    }

    public function removePathology(Pathology $pathology): self
    {
        $this->pathology->removeElement($pathology);

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getResourceFormat(): ?ResourceFormat
    {
        return $this->resourceFormat;
    }

    public function setResourceFormat(?ResourceFormat $resourceFormat): self
    {
        $this->resourceFormat = $resourceFormat;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function onPrePersist()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }
    /**
     * Gets triggered only on update
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updatedAt = new \DateTime();
    }

    public function getPrice(): ?float
    {
        return $this->Price;
    }

    public function setPrice(?float $Price): self
    {
        $this->Price = $Price;

        return $this;
    }

    /**
     * @return Collection|ResourceFile[]
     */
    public function getResourceFiles(): Collection
    {
        return $this->resourceFiles;
    }

    public function addResourceFile(ResourceFile $resourceFile): self
    {
        if (!$this->resourceFiles->contains($resourceFile)) {
            $this->resourceFiles[] = $resourceFile;
            $resourceFile->setResource($this);
        }

        return $this;
    }

    public function removeResourceFile(ResourceFile $resourceFile): self
    {
        if ($this->resourceFiles->removeElement($resourceFile)) {
            // set the owning side to null (unless already changed)
            if ($resourceFile->getResource() === $this) {
                $resourceFile->setResource(null);
            }
        }

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->date_start;
    }

    public function setDateStart(?\DateTimeInterface $date_start): self
    {
        $this->date_start = $date_start;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->date_end;
    }

    public function setDateEnd(?\DateTimeInterface $date_end): self
    {
        $this->date_end = $date_end;

        return $this;
    }
}
