<?php

namespace App\Entity;

use App\Repository\MailRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MailRepository::class)
 */
class Mail extends Service
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=DomainName::class, inversedBy="mail", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $domainName;

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
     * @return DomainName|null
     */
    public function getDomainName(): ?DomainName
    {
        return $this->domainName;
    }

    /**
     * @param DomainName $domainName
     * @return $this
     */
    public function setDomainName(DomainName $domainName): self
    {
        $this->domainName = $domainName;

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
