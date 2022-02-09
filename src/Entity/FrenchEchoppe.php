<?php

namespace App\Entity;

use App\Repository\FrenchEchoppeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FrenchEchoppeRepository::class)
 */
class FrenchEchoppe extends Service
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $onlineDate;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $online;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getOnlineDate(): ?\DateTimeInterface
    {
        return $this->onlineDate;
    }

    /**
     * @param \DateTimeInterface $onlineDate
     * @return $this
     */
    public function setOnlineDate(\DateTimeInterface $onlineDate): self
    {
        $this->onlineDate = $onlineDate;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getOnline(): ?bool
    {
        return $this->online;
    }

    /**
     * @param bool|null $online
     * @return $this
     */
    public function setOnline(?bool $online): self
    {
        $this->online = $online;

        return $this;
    }

    /**
     * @return Client|null
     */
    public function getClient(): ?Client
    {
        return $this->client;
    }

    /**
     * @param Client|null $client
     * @return $this
     */
    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }
}
