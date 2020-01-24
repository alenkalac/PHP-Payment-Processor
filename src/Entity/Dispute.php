<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dispute
 *
 * @ORM\Table(name="disputes")
 * @ORM\Entity
 */
class Dispute
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(name="gamertag", type="string", length=255, nullable=false)
     */
    private $gamertag;

    /**
     * @ORM\Column(name="order_id", type="string", length=64, nullable=false)
     */
    private $orderId;

    /**
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @ORM\Column(name="price", type="decimal", precision=5, scale=2, nullable=false)
     */
    private $price;

    /**
     * @ORM\Column(name="currency", type="string", length=12, nullable=false)
     */
    private $currency;

    /**
     * @ORM\Column(name="gateway", type="string", length=12, nullable=false)
     */
    private $gateway;

    /**
     * @ORM\Column(name="followers", type="integer", nullable=false)
     */
    private $followers;

    /**
     * @ORM\Column(name="dispute_at", type="integer", length=255, nullable=false)
     */
    private $disputeAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGamertag(): ?string
    {
        return $this->gamertag;
    }

    public function setGamertag(string $gamertag): self
    {
        $this->gamertag = $gamertag;

        return $this;
    }

    public function getOrderId(): ?string
    {
        return $this->orderId;
    }

    public function setOrderId(string $orderId): self
    {
        $this->orderId = $orderId;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getGateway(): ?string
    {
        return $this->gateway;
    }

    public function setGateway(string $gateway): self
    {
        $this->gateway = $gateway;

        return $this;
    }

    public function getFollowers(): ?int
    {
        return $this->followers;
    }

    public function setFollowers(int $followers): self
    {
        $this->followers = $followers;

        return $this;
    }

    public function getDisputeAt(): ?int
    {
        return $this->disputeAt;
    }

    public function setDisputeAt(string $disputeAt): self
    {
        $this->disputeAt = $disputeAt;

        return $this;
    }
}
