<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CountryRepository::class)
 * @ORM\Table(name="countries")
 */
class Country
{

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $country;


    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

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

    ////////////////////////////////////////////////////////////////////////////
    //
    //        ARRAY BUILDER
    //
    ////////////////////////////////////////////////////////////////////////////


    public function buildArray(){
      $arr = array();

      $arr['code'] = $this->getCode();
      $arr['country'] = $this->getCountry();


      return $arr;
    }
}
