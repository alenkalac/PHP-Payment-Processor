<?php

namespace App\SymfonyPayments\Items;

class Item {
    private $name;
    private $desc;
    private $unitAmount;
    private $quantity;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * @param mixed $desc
     */
    public function setDesc($desc): void
    {
        $this->desc = $desc;
    }

    /**
     * @return mixed
     */
    public function getUnitAmount()
    {
        return $this->unitAmount;
    }

    /**
     * @param mixed $unitAmount
     */
    public function setUnitAmount($unitAmount): void
    {
        $this->unitAmount = $unitAmount;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity($quantity): void
    {
        $this->quantity = $quantity;
    }
}