<?php

namespace App\Entity;

use App\Repository\ServerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ServerRepository::class)
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
     * @ORM\ManyToMany(targetEntity=Site::class, inversedBy="servers")
     */
    private $sites;

    public function __construct()
    {
        $this->sites = new ArrayCollection();
        $this->domainNames = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|DomainName[]
     */
    public function getDomainNames(): Collection
    {
        return $this->domainNames;
    }

    public function addDomainName(DomainName $domainName): self
    {
        if (!$this->domainNames->contains($domainName)) {
            $this->domainNames[] = $domainName;
            $domainName->setServer($this);
        }

        return $this;
    }

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
     * @return Collection|Site[]
     */
    public function getSites(): Collection
    {
        return $this->sites;
    }

    public function addSite(Site $site): self
    {
        if (!$this->sites->contains($site)) {
            $this->sites[] = $site;
        }

        return $this;
    }

    public function removeSite(Site $site): self
    {
        $this->sites->removeElement($site);

        return $this;
    }
}
