<?php
namespace App\SymfonyPayments\Model;

use App\SymfonyPayments\Model\Interfaces\IOnlineStoreModel;

class SellyModel implements IOnlineStoreModel {

    private $webhookStatus;
    private $data;

    public function __construct($json) {
        $this->webhookStatus = $json->status;
        $this->data = $json;
    }

    public function getWebhookStatus() {
        return $this->webhookStatus;
    }

    public function getTitle() {
        return $this->data->product_title;
    }

    public function getOrderId() {
        return $this->data->id;
    }

    public function getPrice() {
        return $this->data->value;
    }

    public function getCurrency() {
        return $this->data->currency;
    }

    public function getGateway() {
        return $this->data->gateway;
    }

    public function getEmail() {
        return $this->data->email;
    }

    public function getUnixTimestamp() {
        return strtotime($this->data->created_at);
    }

    public function getDateTimestamp() {
        return $this->data->created_at;
    }
}