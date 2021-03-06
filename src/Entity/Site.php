<?php

namespace App\Entity;

use App\Repository\SiteRepository;
use Doctrine\Common\Collections\{ArrayCollection, Collection};
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=SiteRepository::class)
 * @UniqueEntity(
 *     fields= {"name"},
 *     errorPath="name",
 *     message= "Ce nom de site est déjà enregistré"
 *     )
 */
class Site
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
     * @ORM\Column(type="boolean")
     */
    private $online;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="sites", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\OneToMany(targetEntity=SiteClientToServicesBinder::class, mappedBy="site", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     *
     * @var Collection|null
     */
    private $siteClientToServicesBinders;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enable;

    /**
     * @ORM\ManyToMany(targetEntity=Server::class, mappedBy="sites",cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $servers;

    /**
     *
     */
    public function __construct()
    {
        $this->servers = new ArrayCollection();
        $this->siteClientToServicesBinders = new ArrayCollection();
    }

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
     * @param bool $online
     * @return $this
     */
    public function setOnline(bool $online): self
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
     * @param Client $client
     * @return $this
     */
    public function setClient(Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getEnable(): ?bool
    {
        return $this->enable;
    }

    /**
     * @param bool $enable
     * @return $this
     */
    public function setEnable(bool $enable): self
    {
        $this->enable = $enable;

        return $this;
    }

    /**
     * @return Collection|null
     */
    public function getSiteClientToServicesBinders(): ?Collection
    {
        return $this->siteClientToServicesBinders;
    }

    /**
     * @param SiteClientToServicesBinder|null $siteClientToServicesBinder
     * @return $this
     */
    public function addSiteClientToServicesBinder(?SiteClientToServicesBinder $siteClientToServicesBinder): self
    {
        if (!$this->siteClientToServicesBinders->contains($siteClientToServicesBinder)) {
            $this->siteClientToServicesBinders[] = $siteClientToServicesBinder;
            $siteClientToServicesBinder->setSite($this);
        }

        return $this;
    }

    /**
     * @param SiteClientToServicesBinder $siteClientToServicesBinder
     * @return $this
     */
    public function removeSiteClientToServicesBinder(SiteClientToServicesBinder $siteClientToServicesBinder): self
    {
        if ($this->siteClientToServicesBinders->removeElement($siteClientToServicesBinder)) {
            if ($siteClientToServicesBinder->getSite() === $this) {
                $siteClientToServicesBinder->setSite(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|null
     */
    public function getServers(): ?Collection
    {
        return $this->servers;
    }

    /**
     * @param Server|null $server
     * @return $this
     */
    public function addServer(?Server $server): self
    {
        if (!$this->servers->contains($server)) {
            $this->servers[] = $server;
            $server->addSite($this);
        }

        return $this;
    }

    /**
     * @param Server $server
     * @return $this
     */
    public function removeServer(Server $server): self
    {
        if ($this->servers->removeElement($server)) {
            $server->removeSite($this);
        }

        return $this;
    }
}
