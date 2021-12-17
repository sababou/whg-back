<?php

namespace App\Entity;

use App\Repository\GameBrandBlockRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameBrandBlockRepository::class)
 */
class GameBrandBlock
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
    private $launchcode;

    /**
     * @ORM\ManyToOne(targetEntity=Brand::class, inversedBy="gameBrandBlocks")
     * @ORM\JoinColumn(name="brandid", referencedColumnName="id")
     */
    private $brand;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLaunchcode(): ?string
    {
        return $this->launchcode;
    }

    public function setLaunchcode(string $launchcode): self
    {
        $this->launchcode = $launchcode;

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

}
