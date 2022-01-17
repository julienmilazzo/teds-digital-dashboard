<?php

namespace App\Entity;

use App\Repository\SocialNetworkRepository;
use Doctrine\Common\Collections\{ArrayCollection, Collection};
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SocialNetworkRepository::class)
 */
class SocialNetwork extends Service
{
    const FACEBOOK = "Facebook";
    const INSTAGRAM = "Instagram";
    const LINKEDIN = "Linkedin";


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $postByWeek;

    /**
     * @ORM\Column(type="array")
     */
    private $whichSocialNetwork = [];

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\OneToMany(targetEntity=Ad::class, mappedBy="socialNetwork", cascade={"persist"})
     */
    private $ad;

    /**
     *
     */
    public function __construct()
    {
        $this->ad = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getPostByWeek(): ?int
    {
        return $this->postByWeek;
    }

    /**
     * @param int $postByWeek
     * @return $this
     */
    public function setPostByWeek(int $postByWeek): self
    {
        $this->postByWeek = $postByWeek;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getWhichSocialNetwork(): ?array
    {
        return $this->whichSocialNetwork;
    }

    /**
     * @param array $whichSocialNetwork
     * @return $this
     */
    public function setWhichSocialNetwork(array $whichSocialNetwork): self
    {
        $this->whichSocialNetwork = $whichSocialNetwork;

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

    /**
     * @return Collection|Ad[]
     */
    public function getAd(): Collection
    {
        return $this->ad;
    }

    /**
     * @param Ad $ad
     * @return $this
     */
    public function addAd(Ad $ad): self
    {
        if (!$this->ad->contains($ad)) {
            $this->ad[] = $ad;
            $ad->setSocialNetwork($this);
        }

        return $this;
    }

    /**
     * @param Ad $ad
     * @return $this
     */
    public function removeAd(Ad $ad): self
    {
        if ($this->ad->removeElement($ad)) {
            // set the owning side to null (unless already changed)
            if ($ad->getSocialNetwork() === $this) {
                $ad->setSocialNetwork(null);
            }
        }

        return $this;
    }
}
