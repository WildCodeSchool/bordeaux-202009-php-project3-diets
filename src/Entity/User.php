<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository", repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $website;

    /**
     * @ORM\ManyToMany(targetEntity=Expertise::class)
     */
    private $expertise;

    /**
     * @ORM\OneToOne(targetEntity=Picture::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $picture;

    /**
     * @ORM\OneToMany(targetEntity=Service::class, mappedBy="user", orphanRemoval=true)
     */
    private $services;

    /**
     * @ORM\OneToMany(targetEntity=Resource::class, mappedBy="user")
     */
    private $resources;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\OneToMany(targetEntity=RegisteredEvent::class, mappedBy="user", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $registeredEvents;

    /**
     * @Assert\Country
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $country;

    /**
     * @ORM\OneToOne(targetEntity=Dietetician::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $dietetician;

    /**
     * @ORM\OneToOne(targetEntity=Company::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $company;

    /**
     * @ORM\OneToOne(targetEntity=Freelancer::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $freelancer;

    /**
     * @ORM\OneToMany(targetEntity=SecuringPurchases::class, mappedBy="user", orphanRemoval=true)
     */
    private $securingPurchases;



    public function __construct()
    {
        $this->expertise = new ArrayCollection();
        $this->services = new ArrayCollection();
        $this->resources = new ArrayCollection();
        $this->registeredEvents = new ArrayCollection();
        $this->securingPurchases = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }



    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;


        return $this;
    }


    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(?int $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    /**
     * @return Collection|Expertise[]
     */
    public function getExpertise(): Collection
    {
        return $this->expertise;
    }

    public function addExpertise(Expertise $expertise): self
    {
        if (!$this->expertise->contains($expertise)) {
            $this->expertise[] = $expertise;
        }

        return $this;
    }

    public function removeExpertise(Expertise $expertise): self
    {
        $this->expertise->removeElement($expertise);

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
     * @return Collection|Service[]
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): self
    {
        if (!$this->services->contains($service)) {
            $this->services[] = $service;
            $service->setUser($this);
        }

        return $this;
    }

    public function removeService(Service $service): self
    {
        if ($this->services->removeElement($service)) {
            // set the owning side to null (unless already changed)
            if ($service->getUser() === $this) {
                $service->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Resource[]
     */
    public function getResources(): Collection
    {
        return $this->resources;
    }

    public function addResource(?Resource $resource): self
    {
        if (!$this->resources->contains($resource)) {
            $this->resources[] = $resource;
            $resource->setUser($this);
        }

        return $this;
    }

    public function removeResource(?Resource $resource): self
    {
        if ($this->resources->removeElement($resource)) {
            // set the owning side to null (unless already changed)
            if ($resource->getUser() === $this) {
                $resource->setUser(null);
            }
        }
        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection|RegisteredEvent[]
     */
    public function getRegisteredEvents(): Collection
    {
        return $this->registeredEvents;
    }

    public function addRegisteredEvent(?RegisteredEvent $registeredEvent): self
    {
        if (!$this->registeredEvents->contains($registeredEvent)) {
            $this->registeredEvents[] = $registeredEvent;
            $registeredEvent->setUser($this);
        }

        return $this;
    }

    public function removeRegisteredEvent(?RegisteredEvent $registeredEvent): self
    {
        if ($this->registeredEvents->removeElement($registeredEvent)) {
            // set the owning side to null (unless already changed)
            if ($registeredEvent->getUser() === $this) {
                $registeredEvent->setUser(null);
            }
        }
        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getDietetician(): ?Dietetician
    {
        return $this->dietetician;
    }

    public function setDietetician(Dietetician $dietetician): self
    {
        $this->dietetician = $dietetician;

        // set the owning side of the relation if necessary
        if ($dietetician->getUser() !== $this) {
            $dietetician->setUser($this);
        }

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(Company $company): self
    {
        $this->company = $company;

        // set the owning side of the relation if necessary
        if ($company->getUser() !== $this) {
            $company->setUser($this);
        }

        return $this;
    }

    public function getFreelancer(): ?Freelancer
    {
        return $this->freelancer;
    }

    public function setFreelancer(Freelancer $freelancer): self
    {
        $this->freelancer = $freelancer;

        // set the owning side of the relation if necessary
        if ($freelancer->getUser() !== $this) {
            $freelancer->setUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|SecuringPurchases[]
     */
    public function getSecuringPurchases(): Collection
    {
        return $this->securingPurchases;
    }

    public function addSecuringPurchase(SecuringPurchases $securingPurchase): self
    {
        if (!$this->securingPurchases->contains($securingPurchase)) {
            $this->securingPurchases[] = $securingPurchase;
            $securingPurchase->setUser($this);
        }

        return $this;
    }

    public function removeSecuringPurchase(SecuringPurchases $securingPurchase): self
    {
        if ($this->securingPurchases->removeElement($securingPurchase)) {
            // set the owning side to null (unless already changed)
            if ($securingPurchase->getUser() === $this) {
                $securingPurchase->setUser(null);
            }
        }

        return $this;
    }


}
