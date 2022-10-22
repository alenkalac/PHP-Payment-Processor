<?php

namespace App\Payments;

use App\Payments\Processors\PaymentProcessorNotFoundException;
use App\Payments\Processors\PayPalPaymentProcessor;
use App\Payments\Processors\StripePaymentProcessor;

class PaymentProcessors {

    private $gateways = [];

    public function __construct(PayPalPaymentProcessor $palPaymentProcessor, StripePaymentProcessor $stripePaymentProcessor) {
        $this->gateways["paypal"] = $palPaymentProcessor;
        $this->gateways["stripe"] = $stripePaymentProcessor;
    }

    /**
     * @throws PaymentProcessorNotFoundException
     */
    public function get($name):IPaymentProcessor {
        $name = strtolower($name);
        if(!key_exists($name, $this->gateways))
            throw new PaymentProcessorNotFoundException();
        return $this->gateways[$name];
    }

}