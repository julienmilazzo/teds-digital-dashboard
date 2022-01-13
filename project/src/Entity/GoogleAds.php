<?php

namespace App\Entity;

use App\Repository\GoogleAdsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GoogleAdsRepository::class)
 */
class GoogleAds extends Service
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $budget;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBudget(): ?float
    {
        return $this->budget;
    }

    public function setBudget(float $budget): self
    {
        $this->budget = $budget;

        return $this;
    }
}
