<?php

namespace App\Entity;

use App\Repository\SocialNetworkRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SocialNetworkRepository::class)
 */
class SocialNetwork
{
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
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $facebook;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $instagram;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $linkedin;

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
     * @return bool|null
     */
    public function getFacebook(): ?bool
    {
        return $this->facebook;
    }

    /**
     * @param bool|null $facebook
     * @return $this
     */
    public function setFacebook(?bool $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getInstagram(): ?bool
    {
        return $this->instagram;
    }

    /**
     * @param bool|null $instagram
     * @return $this
     */
    public function setInstagram(?bool $instagram): self
    {
        $this->instagram = $instagram;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getLinkedin(): ?bool
    {
        return $this->linkedin;
    }

    /**
     * @param bool|null $linkedin
     * @return $this
     */
    public function setLinkedin(?bool $linkedin): self
    {
        $this->linkedin = $linkedin;

        return $this;
    }
}
