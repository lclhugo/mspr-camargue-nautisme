<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez renseigner votre email')]
    #[Assert\Email(message: 'L\'adresse {{ value }} n\'est pas une adresse valide.')]
    private ?string $emailClient = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateLocation = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'Veuillez renseigner votre nom')]
    private ?string $nameClient = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: 'Veuillez selectionner un équipement')]
    // #[Assert\Choice(message: 'L\'adresse {{ value }} n\'est pas une adresse valide.')]
    private ?Equipment $equipment = null;



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

    public function getEquipment(): ?Equipment
    {
        return $this->equipment;
    }

    public function setEquipment(?Equipment $equipment): self
    {
        $this->equipment = $equipment;

        return $this;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }



}
