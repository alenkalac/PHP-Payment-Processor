<?php

namespace App\SymfonyPayments\Model;

use App\SymfonyPayments\Model\Interfaces\IOnlineStoreModel;

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

    public function getGateway(){
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

    public function getResponseData() {
        return [
            "orderId" => $this->getOrderId(),
            "transactionId" => $this->getTransactionId(),
            "amount" => $this->getPrice(),
            "status" => $this->getStatus(),
            "timestamp" => $this->getUnixTimestamp(),
            "payer_email" => $this->getEmail()
        ];
    }
}