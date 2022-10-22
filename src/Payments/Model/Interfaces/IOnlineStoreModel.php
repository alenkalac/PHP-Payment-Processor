<?php

namespace App\Payments\Model\Interfaces;

interface IOnlineStoreModel {
    public function getWebhookStatus();
    public function getTitle();
    public function getOrderId();
    public function getPrice();
    public function getCurrency();
    public function getGateway();
    public function getEmail();
    public function getUnixTimestamp();
    public function getDateTimestamp();
}