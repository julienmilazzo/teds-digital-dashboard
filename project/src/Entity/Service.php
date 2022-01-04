<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\ORM\Mapping as ORM;

class Service
{
    /**
     * @ORM\Column(type="string", length=255)
     */
     protected $provider;

    /**
     * @ORM\Column(type="string", length=255)
     */
     protected $offer;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
     protected $price;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
     protected $invoicedPrice;

    /**
     * @ORM\Column(type="string", length=255)
     */
     protected $renewalType;

    /**
     * @ORM\Column(type="date")
     */
     protected $renewalDate;

    /**
     * @return mixed
     */
    public function getRenewalDate()
    {
        return $this->renewalDate;
    }

    /**
     * @param string $provider
     * @return $this
     */
    public function setProvider(string $provider): self
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getOffer(): ?string
    {
        return $this->offer;
    }

    /**
     * @param string $offer
     * @return $this
     */
    public function setOffer(string $offer): self
    {
        $this->offer = $offer;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float|null $price
     * @return $this
     */
    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getInvoicedPrice(): ?float
    {
        return $this->invoicedPrice;
    }

    /**
     * @param float|null $invoicedPrice
     * @return $this
     */
    public function setInvoicedPrice(?float $invoicedPrice): self
    {
        $this->invoicedPrice = $invoicedPrice;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRenewalType(): ?string
    {
        return $this->renewalType;
    }

    /**
     * @param string $renewalType
     * @return $this
     */
    public function setRenewalType(string $renewalType): self
    {
        $this->renewalType = $renewalType;

        return $this;
    }

    /**
     * @param mixed $renewalDate
     */
    public function setRenewalDate($renewalDate): void
    {
        $this->renewalDate = $renewalDate;
    }

    /**
     * @return string|null
     */
    public function getProvider(): ?string
    {
        return $this->provider;
    }
}
