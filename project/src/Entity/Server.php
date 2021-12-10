<?php

namespace App\Entity;

use App\Repository\ServerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ServerRepository::class)
 */
class Server
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
     * @ORM\Column(type="date")
     */
    private $RenewalDate;

    /**
     * @ORM\OneToMany(targetEntity=Site::class, mappedBy="server")
     */
    private $sites;

    /**
     * @ORM\OneToMany(targetEntity=DomainName::class, mappedBy="server")
     */
    private $domainNames;

    public function __construct()
    {
        $this->sites = new ArrayCollection();
        $this->domainNames = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
            $site->setServer($this);
        }

        return $this;
    }

    public function removeSite(Site $site): self
    {
        if ($this->sites->removeElement($site)) {
            // set the owning side to null (unless already changed)
            if ($site->getServer() === $this) {
                $site->setServer(null);
            }
        }

        return $this;
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
}
