<?php

namespace App\SymfonyPayments;

use App\SymfonyPayments\Processors\PaymentProcessorNotFoundException;
use App\SymfonyPayments\Processors\PayPalPaymentProcessor;
use App\SymfonyPayments\Processors\StripePaymentProcessor;

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