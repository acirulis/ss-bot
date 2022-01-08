<?php

namespace App\Entity;

use App\Repository\HouseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HouseRepository::class)]
class House
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $description;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $address;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $area;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $floors;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $total_area;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $price;

    #[ORM\Column(type: 'datetime')]
    private $date_added;

    #[ORM\Column(type: 'string', length: 255)]
    private $href;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getArea(): ?string
    {
        return $this->area;
    }

    public function setArea(?string $area): self
    {
        $this->area = $area;

        return $this;
    }

    public function getFloors(): ?string
    {
        return $this->floors;
    }

    public function setFloors(?string $floors): self
    {
        $this->floors = $floors;

        return $this;
    }

    public function getTotalArea(): ?string
    {
        return $this->total_area;
    }

    public function setTotalArea(?string $total_area): self
    {
        $this->total_area = $total_area;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDateAdded(): ?\DateTimeInterface
    {
        return $this->date_added;
    }

    public function setDateAdded(\DateTimeInterface $date_added): self
    {
        $this->date_added = $date_added;

        return $this;
    }

    public function getHref(): ?string
    {
        return $this->href;
    }

    public function setHref(string $href): self
    {
        $this->href = $href;

        return $this;
    }
}
