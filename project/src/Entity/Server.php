<?php

namespace App\Entity;

use App\Repository\ServerRepository;
use Doctrine\Common\Collections\{ArrayCollection, Collection};
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ServerRepository::class)
 * @UniqueEntity(
 *     fields= {"name"},
 *     errorPath="name",
 *     message= "Ce nom de serveur est déjà enregistré"
 *     )
 */
class Server extends Service
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
     * @ORM\ManyToMany(targetEntity=Site::class, inversedBy="servers", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $sites;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="servers", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\OneToMany(targetEntity=DomainName::class, mappedBy="server", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     *
     * @var Collection|null
     */
    private $domainNames;

    /**
     * @ORM\OneToMany(targetEntity=ClickAndCollect::class, mappedBy="server", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     *
     * @var Collection|null
     */
    private $clickAndCollects;

    protected int $siteClientToServicesBinderId;

    public function __construct()
    {
        $this->sites = new ArrayCollection();
        $this->domainNames = new ArrayCollection();
        $this->clickAndCollects =new ArrayCollection();
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
        }

        return $this;
    }

    /**
     * @param Site $site
     * @return $this
     */
    public function removeSite(Site $site): self
    {
        $this->sites->removeElement($site);

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
     * @return Collection|null
     */
    public function getDomainNames(): ?Collection
    {
        return $this->domainNames;
    }

    /**
     * @param DomainName|null $domainName
     * @return $this
     */
    public function addDomainName(?DomainName $domainName): self
    {
        if (!$this->domainNames->contains($domainName)) {
            $this->domainNames[] = $domainName;
            $domainName->setServer($this);
        }

        return $this;
    }

    /**
     * @param DomainName $domainName
     * @return $this
     */
    public function removeDomainName(DomainName $domainName): self
    {
        if ($this->domainNames->removeElement($domainName)) {
            // set the owning side to null (unless already changed)
            if ($domainName->getServer() === $this) {
                $domainName->setServer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|null
     */
    public function getClickAndCollects(): ?Collection
    {
        return $this->clickAndCollects;
    }

    /**
     * @param ClickAndCollect|null $clickAndCollect
     * @return $this
     */
    public function addClickAndCollect(?ClickAndCollect $clickAndCollect): self
    {
        if (!$this->clickAndCollects->contains($clickAndCollect)) {
            $this->clickAndCollects[] = $clickAndCollect;
            $clickAndCollect->setServer($this);
        }

        return $this;
    }

    /**
     * @param ClickAndCollect $clickAndCollect
     * @return $this
     */
    public function removeClickAndCollect(ClickAndCollect $clickAndCollect): self
    {
        if ($this->clickAndCollects->removeElement($clickAndCollect)) {
            // set the owning side to null (unless already changed)
            if ($clickAndCollect->getServer() === $this) {
                $clickAndCollect->setServer(null);
            }
        }

        return $this;
    }
}
