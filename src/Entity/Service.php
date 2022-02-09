<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\ORM\Mapping as ORM;

class Service
{
    const DOMAIN_NAME = 'DomainName';
    const SERVER = 'Server';
    const CLICK_AND_COLLECT = 'ClickAndCollect';
    const MAIL = 'Mail';
    const SOCIAL_NETWORK = 'SocialNetwork';
    const AD = 'Ad';
    const FRENCH_ECHOPPE = 'FrenchEchoppe';

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
     protected $cost;

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
     * @ORM\Column(type="date")
     */
    protected $startDate;

    /**
     * @ORM\Column(type="string", length=10000, nullable=true)
     */
    protected $commentary;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $enable;

    /**
     * @ORM\Column(type="integer")
     */
    protected int $siteClientToServicesBinderId;

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
    public function getCost(): ?float
    {
        return $this->cost;
    }

    /**
     * @param float|null $cost
     * @return $this
     */
    public function setCost(?float $cost): self
    {
        $this->cost = $cost;

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
     * @return mixed
     */
    public function getRenewalDate()
    {
        return $this->renewalDate;
    }

    /**
     * @param mixed $renewalDate
     */
    public function setRenewalDate($renewalDate): void
    {
        $this->renewalDate = $renewalDate;
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
    public function getProvider(): ?string
    {
        return $this->provider;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param mixed $startDate
     */
    public function setStartDate($startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return int
     */
    public function getSiteClientToServicesBinderId(): int
    {
        return $this->siteClientToServicesBinderId;
    }

    /**
     * @param int|null $siteClientToServicesBinderId
     */
    public function setSiteClientToServicesBinderId(?int $siteClientToServicesBinderId): void
    {
        $this->siteClientToServicesBinderId = $siteClientToServicesBinderId;
    }

    /**
     * @return mixed
     */
    public function getCommentary()
    {
        return $this->commentary;
    }

    /**
     * @param mixed $commentary
     */
    public function setCommentary($commentary): void
    {
        $this->commentary = $commentary;
    }

    /**
     * @return mixed
     */
    public function getEnable()
    {
        return $this->enable;
    }

    /**
     * @param mixed $enable
     */
    public function setEnable($enable): void
    {
        $this->enable = $enable;
    }
}
