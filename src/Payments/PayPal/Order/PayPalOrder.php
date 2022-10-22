<?php

namespace App\Payments\PayPal\Order;

use App\Payments\Items\Item;
use App\Payments\PayPal\Interfaces\PayPalTransactionInterface;

class PayPalOrder implements PayPalTransactionInterface {

    private const CHECKOUT_URL = "/v2/checkout/orders";

    private $return_url;
    private $cancel_url;
    private $amount;
    private $currency;
    private $items = [];

    /**
     * @return mixed
     */
    public function getReturnUrl() {
        return $this->return_url;
    }

    /**
     * @param mixed $return_url
     */
    public function setReturnUrl($return_url) {
        $this->return_url = $return_url;
    }

    /**
     * @return mixed
     */
    public function getCancelUrl(){
        return $this->cancel_url;
    }

    /**
     * @param mixed $cancel_url
     */
    public function setCancelUrl($cancel_url) {
        $this->cancel_url = $cancel_url;
    }

    /**
     * @return mixed
     */
    public function getAmount(){
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount) {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getCurrency(){
        return $this->currency;
    }

    /**
     * @param mixed $currency
     */
    public function setCurrency($currency) {
        $this->currency = $currency;
    }

    /**
     * @return array
     */
    public function getItems(): array{
        return $this->items;
    }

    /**
     * @param Item $items
     */
    public function addItem(Item $items): void {
        $this->items[] = $items;
    }

    public function getUrl(): string {
        return self::CHECKOUT_URL;
    }

    public function getRequestBody(): array {
        $body = [];
        $body['intent'] = "CAPTURE";
        $body['application_context'] = [
            "return_url" => $this->getReturnUrl(),
            "cancel_url" => $this->getCancelUrl(),
            "shipping_preference" => "NO_SHIPPING",
        ];
        $body['purchase_units'] = [];
        $body['purchase_units'][] = [
            "amount" => [
                "currency_code" => $this->getCurrency(),
                "value" => $this->getAmount(),
            ]
        ];

        if(!empty($this->getItems())) {
            $body['purchase_units'][0]["amount"]["breakdown"] = [
                "item_total" => [
                    "value" => $this->getAmount(),
                    "currency_code" => $this->getCurrency()
                ]
            ];

            $body['purchase_units'][0]['items'] = [];

            /** @var Item $item */
            foreach ($this->getItems() as $item) {
                $body['purchase_units'][0]['items'][] = [
                    "name" => $item->getName(),
                    "description" => $item->getDesc(),
                    "quantity" => $item->getQuantity(),
                    "unit_amount" => [
                        "value" => $item->getUnitAmount(),
                        "currency_code" => $this->getCurrency()
                    ]
                ];
            }
        }

        return $body;
    }
}
