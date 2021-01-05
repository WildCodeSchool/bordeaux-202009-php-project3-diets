<?php

namespace App\Entity;

use App\Repository\RegisteredEventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RegisteredEventRepository::class)
 */
class RegisteredEvent
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isOrganizer;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="registeredEvents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $event;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="registeredEvents")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsOrganizer(): ?bool
    {
        return $this->isOrganizer;
    }

    public function setIsOrganizer(bool $isOrganizer): self
    {
        $this->isOrganizer = $isOrganizer;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

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
}
