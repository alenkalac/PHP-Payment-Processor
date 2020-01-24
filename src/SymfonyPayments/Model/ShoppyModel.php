<?php

namespace App\SymfonyPayments\Model;

use App\SymfonyPayments\Utils\ShoppyUtils;
use App\SymfonyPayments\Utils\WebhookUtils;

class ShoppyModel {
    private $webhookStatus;
    private $data;

    public function __construct($json) {
        $this->webhookStatus = $json->webhook_type;
        $this->data = $json->data;
    }

    public function getWebhookStatus() {
        return  $this->webhookStatus;
    }

    public function getTitle() {
        try {
            $title = $this->data->order->product->title;
        } catch (\Exception $e) {
            $title = $this->data->product->title;
        }
        return $title;
    }

    public function getOrderId() {
        return $this->data->order->id;
    }

    public function getPrice() {
        try {
            $price = $this->data->order->product->price;
        } catch (\Exception $e) {
            $price = $this->data->product->price;
        }
        return $price;
    }

    public function getCurrency() {
        return $this->data->order->currency;
    }

    public function getGateway() {
        return $this->data->order->gateway;
    }

    public function getEmail() {
        return $this->data->order->email;
    }

    public function getUnixTimestamp() {
        return strtotime($this->data->order->paid_at == null ? time() : $this->data->order->paid_at);
    }

    public function getDateTimestamp() {
        return $this->data->order->paid_at == null ? time() : $this->data->order->paid_at;
    }
}