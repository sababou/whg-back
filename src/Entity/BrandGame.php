<?php

namespace App\Entity;

use App\Repository\BrandGameRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BrandGameRepository::class)
 * @ORM\Table(name="brand_games")
 */
class BrandGame
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
     * @ORM\Column(type="string", length=255)
     */
    private $category;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $hot;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $new;

    /**
     * @ORM\ManyToOne(targetEntity=Brand::class, inversedBy="brandGames")
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


    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getHot(): ?bool
    {
        return $this->hot;
    }

    public function setHot(?bool $hot): self
    {
        $this->hot = $hot;

        return $this;
    }

    public function getNew(): ?bool
    {
        return $this->new;
    }

    public function setNew(?bool $new): self
    {
        $this->new = $new;

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


    ////////////////////////////////////////////////////////////////////////////
    //
    //        ARRAY BUILDER
    //
    ////////////////////////////////////////////////////////////////////////////


    public function buildArray(){
      $arr = array();

      $arr['id'] = $this->getId();
      $arr['name'] = $this->getName();

      return $arr;
    }
}
