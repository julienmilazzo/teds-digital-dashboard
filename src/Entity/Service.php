<?php

namespace App\Entity;

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
     * @ORM\Column(type="integer", nullable=true)
     */
    protected int $siteClientToServicesBinderId;

    /**
     * @return mixed
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @param mixed $provider
     * @return Service
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOffer()
    {
        return $this->offer;
    }

    /**
     * @param mixed $offer
     * @return Service
     */
    public function setOffer($offer)
    {
        $this->offer = $offer;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @param mixed $cost
     * @return Service
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInvoicedPrice()
    {
        return $this->invoicedPrice;
    }

    /**
     * @param mixed $invoicedPrice
     * @return Service
     */
    public function setInvoicedPrice($invoicedPrice)
    {
        $this->invoicedPrice = $invoicedPrice;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRenewalType()
    {
        return $this->renewalType;
    }

    /**
     * @param mixed $renewalType
     * @return Service
     */
    public function setRenewalType($renewalType)
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
     * @return Service
     */
    public function setRenewalDate($renewalDate)
    {
        $this->renewalDate = $renewalDate;
        return $this;
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
     * @return Service
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
        return $this;
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
     * @return Service
     */
    public function setCommentary($commentary)
    {
        $this->commentary = $commentary;
        return $this;
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
     * @return Service
     */
    public function setEnable($enable)
    {
        $this->enable = $enable;
        return $this;
    }

    /**
     * @return int
     */
    public function getSiteClientToServicesBinderId(): int
    {
        return $this->siteClientToServicesBinderId;
    }

    /**
     * @param int $siteClientToServicesBinderId
     * @return Service
     */
    public function setSiteClientToServicesBinderId(int $siteClientToServicesBinderId): Service
    {
        $this->siteClientToServicesBinderId = $siteClientToServicesBinderId;
        return $this;
    }


}
