<?php

namespace App\Entity;

use App\Repository\ClickAndCollectRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClickAndCollectRepository::class)
 */
class ClickAndCollect extends Service
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
     * @ORM\Column(type="boolean")
     */
    private $enable;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity=Site::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $site;

    /**
     * @ORM\ManyToOne(targetEntity=Server::class, inversedBy="clickAndCollects" ,cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $server;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getOnlineDate()
    {
        return $this->onlineDate;
    }

    /**
     * @param mixed $onlineDate
     */
    public function setOnlineDate($onlineDate): void
    {
        $this->onlineDate = $onlineDate;
    }

    /**
     * @return mixed
     */
    public function getOnline()
    {
        return $this->online;
    }

    /**
     * @param mixed $online
     */
    public function setOnline($online): void
    {
        $this->online = $online;
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
     */
    public function setEnable($enable): void
    {
        $this->enable = $enable;
    }

    /**
     * @return Client
     */
    public function getClient(): Client
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
}
