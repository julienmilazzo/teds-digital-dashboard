<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\{ArrayCollection, Collection};
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 * @UniqueEntity(
 *     fields= {"name"},
 *     errorPath="name",
 *     message= "Ce client existe déjà sous ce nom."
 *     )
 */
class Client
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $zipCode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    /**
     * @ORM\OneToMany(targetEntity=Site::class, mappedBy="client", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     *
     * @var Collection|null
     */
    private $sites;

    /**
     * @ORM\OneToMany(targetEntity=SiteClientToServicesBinder::class, mappedBy="client", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     *
     * @var Collection|null
     */
    private $siteClientToServicesBinders;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enable;

    /**
     * @ORM\OneToMany(targetEntity=Server::class, mappedBy="client", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     *
     * @var Collection|null
     */
    private $servers;

    /**
     *
     */
    public function __construct()
    {
        $this->sites = new ArrayCollection();
        $this->siteClientToServicesBinders = new ArrayCollection();
        $this->servers = new ArrayCollection();
    }

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
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return $this
     */
    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return $this
     */
    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    /**
     * @param string $zipCode
     * @return $this
     */
    public function setZipCode(string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return $this
     */
    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Collection|null
     */
    public function getSites(): ?Collection
    {
        return $this->sites;
    }

    /**
     * @param Site|null $site
     * @return $this
     */
    public function addSite(?Site $site): self
    {
        if (!$this->sites->contains($site)) {
            $this->sites[] = $site;
            $site->setClient($this);
        }

        return $this;
    }

    /**
     * @param Site $site
     * @return $this
     */
    public function removeSite(Site $site): self
    {
        if ($this->sites->removeElement($site)) {
            // set the owning side to null (unless already changed)
            if ($site->getClient() === $this) {
                $site->setClient(null);
            }
        }

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
            $siteClientToServicesBinder->setClient($this);
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
            // set the owning side to null (unless already changed)
            if ($siteClientToServicesBinder->getClient() === $this) {
                $siteClientToServicesBinder->setClient(null);
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
            $server->setClient($this);
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
            // set the owning side to null (unless already changed)
            if ($server->getClient() === $this) {
                $server->setClient(null);
            }
        }

        return $this;
    }
}
