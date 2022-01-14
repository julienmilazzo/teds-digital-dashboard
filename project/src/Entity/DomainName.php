<?php

namespace App\Entity;

use App\Repository\DomainNameRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=DomainNameRepository::class)
 * @UniqueEntity(
 *     fields= {"url"},
 *     errorPath="url",
 *     message= "Ce nom de domain est déjà enregistré"
 *     )
 */
class DomainName extends Service
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
     * @ORM\ManyToOne(targetEntity=Client::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity=Site::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $site;

    /**
     * @ORM\ManyToOne(targetEntity=Server::class, inversedBy="domainNames", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $server;

    /**
     * @ORM\OneToOne(targetEntity=Mail::class, mappedBy="domainName", cascade={"persist", "remove"})
     */
    private $mail;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;

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
     * @param Client $client
     * @return $this
     */
    public function setClient(Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Site|null
     */
    public function getSite(): ?Site
    {
        return $this->site;
    }

    /**
     * @param Site|null $site
     * @return $this
     */
    public function setSite(?Site $site): self
    {
        $this->site = $site;

        return $this;
    }

    /**
     * @return Server|null
     */
    public function getServer(): ?Server
    {
        return $this->server;
    }

    /**
     * @param Server|null $server
     * @return $this
     */
    public function setServer(?Server $server): self
    {
        $this->server = $server;

        return $this;
    }

    public function getMail(): ?Mail
    {
        return $this->mail;
    }

    public function setMail(Mail $mail): self
    {
        // set the owning side of the relation if necessary
        if ($mail->getDomainName() !== $this) {
            $mail->setDomainName($this);
        }

        $this->mail = $mail;

        return $this;
    }
}
