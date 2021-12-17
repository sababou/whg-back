<?php

namespace App\Entity;

use App\Repository\GameCountryBlockRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameCountryBlockRepository::class)
 */
class GameCountryBlock
{


    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=255)
     */
    private $launchcode;



    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=255)
     */
    private $country;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=Brand::class, inversedBy="gameCountryBlocks")
     * @ORM\JoinColumn(name="brandid", referencedColumnName="id")
     */
    private $brand;



    public function getLaunchcode(): ?string
    {
        return $this->launchcode;
    }

    public function setLaunchcode(string $launchcode): self
    {
        $this->launchcode = $launchcode;

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
