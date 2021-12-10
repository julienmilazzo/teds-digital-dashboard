<?php

namespace App\Entity;

use App\Repository\DomainNameRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DomainNameRepository::class)
 */
class DomainName
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
    private $url;

    /**
     * @ORM\Column(type="date")
     */
    private $RenewalDate;

    /**
     * @ORM\ManyToOne(targetEntity=Server::class, inversedBy="domainNames")
     * @ORM\JoinColumn(nullable=false)
     */
    private $server;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getRenewalDate(): ?\DateTimeInterface
    {
        return $this->RenewalDate;
    }

    public function setRenewalDate(\DateTimeInterface $RenewalDate): self
    {
        $this->RenewalDate = $RenewalDate;

        return $this;
    }

    public function getServer(): ?Server
    {
        return $this->server;
    }

    public function setServer(?Server $server): self
    {
        $this->server = $server;

        return $this;
    }
}
