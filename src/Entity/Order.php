<?php

namespace App\Entity;

use App\SymfonyPayments\Model\ShoppyModel;
use Doctrine\ORM\Mapping as ORM;

/**
 * Order
 *
 * @ORM\Table(name="orders")
 * @ORM\Entity
 */
class Order
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @ORM\Column(name="order_id", type="string", length=64, nullable=false)
     */
    private $orderId;

    /**
     * @var string
     *
     * @ORM\Column(name="gateway", type="string", length=12, nullable=false)
     */
    private $gateway;

    /**
     * @ORM\Column(name="price", type="decimal", precision=5, scale=2, nullable=false)
     */
    private $price;

    /**
     * @ORM\Column(name="currency", type="string", length=12, nullable=false)
     */
    private $currency;

    /**
     * @ORM\Column(name="purchased", type="string", length=255, nullable=false)
     */
    private $purchased;

    /**
     * @ORM\Column(name="time", type="string", length=12, nullable=false)
     */
    private $time;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getOrderId(): ?string
    {
        return $this->orderId;
    }

    public function setOrderId(string $orderId): self
    {
        $this->orderId = $orderId;

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

    public function getPurchased(): ?string
    {
        return $this->purchased;
    }

    public function setPurchased(string $purchased): self
    {
        $this->purchased = $purchased;

        return $this;
    }

    public function getTime(): ?string
    {
        return $this->time;
    }

    public function setTime(string $paidAt): self
    {
        $this->time = $paidAt;

        return $this;
    }

    public function setFromModel(ShoppyModel $shoppyModel) {
        $this->setEmail($shoppyModel->getEmail())
            ->setOrderId($shoppyModel->getOrderId())
            ->setGateway($shoppyModel->getGateway())
            ->setPrice($shoppyModel->getPrice())
            ->setCurrency($shoppyModel->getCurrency())
            ->setTime($shoppyModel->getUnixTimestamp());

        return $this;
    }
}
