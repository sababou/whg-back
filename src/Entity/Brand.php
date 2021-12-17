<?php

namespace App\Entity;

use App\Repository\BrandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BrandRepository::class)
 * @ORM\Table(name="brands")
 */
class Brand
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, name="brand")
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=BrandGame::class, mappedBy="brand")
     */
    private $brandGames;

    /**
     * @ORM\OneToMany(targetEntity=GameBrandBlock::class, mappedBy="brand")
     */
    private $gameBrandBlocks;

    /**
     * @ORM\OneToMany(targetEntity=GameCountryBlock::class, mappedBy="brand")
     */
    private $gameCountryBlocks;

    public function __construct()
    {
        $this->brandGames = new ArrayCollection();
        $this->gameBrandBlocks = new ArrayCollection();
        $this->gameCountryBlocks = new ArrayCollection();
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|BrandGame[]
     */
    public function getBrandGames(): Collection
    {
        return $this->brandGames;
    }

    public function addBrandGame(BrandGame $brandGame): self
    {
        if (!$this->brandGames->contains($brandGame)) {
            $this->brandGames[] = $brandGame;
            $brandGame->setBrand($this);
        }

        return $this;
    }

    public function removeBrandGame(BrandGame $brandGame): self
    {
        if ($this->brandGames->removeElement($brandGame)) {
            // set the owning side to null (unless already changed)
            if ($brandGame->getBrand() === $this) {
                $brandGame->setBrand(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|GameBrandBlock[]
     */
    public function getGameBrandBlocks(): Collection
    {
        return $this->gameBrandBlocks;
    }

    public function addGameBrandBlock(GameBrandBlock $gameBrandBlock): self
    {
        if (!$this->gameBrandBlocks->contains($gameBrandBlock)) {
            $this->gameBrandBlocks[] = $gameBrandBlock;
            $gameBrandBlock->setBrand($this);
        }

        return $this;
    }

    public function removeGameBrandBlock(GameBrandBlock $gameBrandBlock): self
    {
        if ($this->gameBrandBlocks->removeElement($gameBrandBlock)) {
            // set the owning side to null (unless already changed)
            if ($gameBrandBlock->getBrand() === $this) {
                $gameBrandBlock->setBrand(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|GameCountryBlock[]
     */
    public function getGameCountryBlocks(): Collection
    {
        return $this->gameCountryBlocks;
    }

    public function addGameCountryBlock(GameCountryBlock $gameCountryBlock): self
    {
        if (!$this->gameCountryBlocks->contains($gameCountryBlock)) {
            $this->gameCountryBlocks[] = $gameCountryBlock;
            $gameCountryBlock->setBrand($this);
        }

        return $this;
    }

    public function removeGameCountryBlock(GameCountryBlock $gameCountryBlock): self
    {
        if ($this->gameCountryBlocks->removeElement($gameCountryBlock)) {
            // set the owning side to null (unless already changed)
            if ($gameCountryBlock->getBrand() === $this) {
                $gameCountryBlock->setBrand(null);
            }
        }

        return $this;
    }
}
