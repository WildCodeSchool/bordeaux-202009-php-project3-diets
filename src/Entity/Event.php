<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Event
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
     * @ORM\Column(type="datetime")
     */
    private $dateStart;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateEnd;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=EventFormat::class, inversedBy="events")
     */
    private $eventFormat;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $eventIsValidated;

    /**
     * @ORM\OneToOne(targetEntity=Picture::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn (onDelete="CASCADE")
     */
    private $picture;

    /**
     * @ORM\OneToMany(targetEntity=RegisteredEvent::class, mappedBy="event", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $registeredEvents;

    public function __construct()
    {
        $this->registeredEvents = new ArrayCollection();
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

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(\DateTimeInterface $dateStart): self
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(\DateTimeInterface $dateEnd): self
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

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

    public function getEventFormat(): ?EventFormat
    {
        return $this->eventFormat;
    }

    public function setEventFormat(?EventFormat $eventFormat): self
    {
        $this->eventFormat = $eventFormat;

        return $this;
    }

    public function getEventIsValidated(): ?bool
    {
        return $this->eventIsValidated;
    }

    public function setEventIsValidated(?bool $eventIsValidated): self
    {
        $this->eventIsValidated = $eventIsValidated;

        return $this;
    }

    public function getPicture(): ?Picture
    {
        return $this->picture;
    }

    public function setPicture(?Picture $picture): self
    {
        $this->picture = $picture;

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

    /**
     * @return Collection|RegisteredEvent[]
     */
    public function getRegisteredEvents(): Collection
    {
        return $this->registeredEvents;
    }

    public function addRegisteredEvent(RegisteredEvent $registeredEvent): self
    {
        if (!$this->registeredEvents->contains($registeredEvent)) {
            $this->registeredEvents[] = $registeredEvent;
            $registeredEvent->setEvent($this);
        }

        return $this;
    }

    public function removeRegisteredEvent(RegisteredEvent $registeredEvent): self
    {
        if ($this->registeredEvents->removeElement($registeredEvent)) {
            // set the owning side to null (unless already changed)
            if ($registeredEvent->getEvent() === $this) {
                $registeredEvent->setEvent(null);
            }
        }

        return $this;
    }
}
