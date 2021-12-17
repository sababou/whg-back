<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game
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
    private $name;

    /**
     * @ORM\Column(type="float")
     */
    private $rtp;

    /**
     * @ORM\ManyToOne(targetEntity=GameProvider::class, inversedBy="games")
     */
    private $gameProvider;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getRtp(): ?float
    {
        return $this->rtp;
    }

    public function setRtp(float $rtp): self
    {
        $this->rtp = $rtp;

        return $this;
    }

    public function getGameProvider(): ?GameProvider
    {
        return $this->gameProvider;
    }

    public function setGameProvider(?GameProvider $gameProvider): self
    {
        $this->gameProvider = $gameProvider;

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
      $arr['launchcode'] = $this->getLaunchcode();
      $arr['name'] = $this->getName();
      $arr['rtp'] = $this->getRtp();

      try{
        $arr['game_provider'] = $this->getGameProvider()->buildArray();
      }catch(\Exception $e){
        // do nothing
      }

      return $arr;
    }
}
