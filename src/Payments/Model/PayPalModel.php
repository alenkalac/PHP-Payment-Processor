<?php

namespace App\Payments\Model;

use App\Payments\Model\Interfaces\IOnlineStoreModel;

class PayPalModel implements IOnlineStoreModel {
    private $data;

    public function __construct($data){
        $this->data = $data;
    }

    public function getWebhookStatus(){
        // TODO: Implement getWebhookStatus() method.
    }

    public function getStatus() {
        return $this->data->status;
    }

    public function getTitle(){
        // TODO: Implement getTitle() method.
    }

    public function getOrderId(){
        return $this->data->id;
    }

    public function getTransactionId() {
        return $this->data->purchase_units[0]->payments->captures[0]->id;
    }

    public function getPrice(){
        return $this->data->purchase_units[0]->payments->captures[0]->amount->value;
    }

    public function getCurrency(){
        return $this->data->purchase_units[0]->payments->captures[0]->amount->currency_code;
    }

    public function getGateway(): string {
        return "PayPal";
    }

    public function getEmail(){
        return $this->data->payer->email_address;
    }

    public function getUnixTimestamp(){
        return strtotime($this->getDateTimestamp());
    }

    public function getDateTimestamp(){
        return $this->data->purchase_units[0]->payments->captures[0]->create_time;
    }

    public function getPayer() {
        return $this->data->payer;
    }

    public function getResponseData(): array {
        $d = [
            "orderId" => $this->getOrderId(),
            "transactionId" => $this->getTransactionId(),
            "amount" => $this->getPrice(),
            "status" => $this->getStatus(),
            "timestamp" => $this->getUnixTimestamp(),
            "payer" => json_encode($this->getPayer())
        ];
        return $d;
    }
}
