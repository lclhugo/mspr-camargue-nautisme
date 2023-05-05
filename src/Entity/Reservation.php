<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $emailClient = null;

    #[ORM\OneToMany(mappedBy: 'reservation', targetEntity: Equipment::class)]
    private Collection $equipments;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateLocation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nameClient = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->equipments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmailClient(): ?string
    {
        return $this->emailClient;
    }

    public function setEmailClient(string $emailClient): self
    {
        $this->emailClient = $emailClient;

        return $this;
    }

    /**
     * @return Collection<int, Equipment>
     */
    public function getEquipments(): Collection
    {
        return $this->equipments;
    }

    public function addEquipments(Equipment $equipment): self
    {
        if (!$this->equipments->contains($equipment)) {
            $this->equipments->add($equipment);
            $equipment->setReservation($this);
        }

        return $this;
    }

    public function removeEquipments(Equipment $equipment): self
    {
        if ($this->equipments->removeElement($equipment)) {
            // set the owning side to null (unless already changed)
            if ($equipment->getReservation() === $this) {
                $equipment->setReservation(null);
            }
        }

        return $this;
    }

    public function getDateLocation(): ?\DateTimeInterface
    {
        return $this->dateLocation;
    }

    public function setDateLocation(\DateTimeInterface $dateLocation): self
    {
        $this->dateLocation = $dateLocation;

        return $this;
    }

    public function getNameClient(): ?string
    {
        return $this->nameClient;
    }

    public function setNameClient(?string $nameClient): self
    {
        $this->nameClient = $nameClient;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
