<?php
namespace App\SymfonyPayments\PayPal;

use App\SymfonyPayments\PayPal\interfaces\PayPalBuilderInterface;
use App\SymfonyPayments\PayPal\Order\PayPalItem;

class PayPalOrderBuilder implements PayPalBuilderInterface {

    private $payPalOrder;

    public function __construct() {
        $this->payPalOrder = new PayPalOrder();
    }

    /**
     * @param mixed $return_url
     * @return PayPalOrderBuilder
     */
    public function setReturnUrl($return_url): PayPalOrderBuilder {
        $this->payPalOrder->setReturnUrl($return_url);
        return $this;
    }

    /**
     * @param mixed $cancel_url
     * @return PayPalOrderBuilder
     */
    public function setCancelUrl($cancel_url): PayPalOrderBuilder {
        $this->payPalOrder->setCancelUrl($cancel_url);
        return $this;
    }


    /**
     * @param mixed $amount
     * @return PayPalOrderBuilder
     */
    public function setAmount($amount): PayPalOrderBuilder {
        $this->payPalOrder->setAmount($amount);
        return $this;
    }

    /**
     * @param mixed $currency
     * @return PayPalOrderBuilder
     */
    public function setCurrency($currency): PayPalOrderBuilder {
        $this->payPalOrder->setCurrency($currency);
        return $this;
    }

    public function addItem(PayPalItem $item) {
        $this->payPalOrder->addItem($item);
    }

    public function build() {
        return $this->payPalOrder;
    }
}