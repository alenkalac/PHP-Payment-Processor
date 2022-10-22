<?php

namespace App\SymfonyPayments\Items;

use App\SymfonyPayments\PayPal\PayPalClient;
use Exception;

class RequestItemParser {

    private static $required_fields = [
        PayPalClient::FIELD_AMOUNT,
        PayPalClient::FIELD_CURRENCY,
        PayPalClient::FIELD_RETURN_URL,
        PayPalClient::FIELD_CANCEL_URL
    ];

    /**
     * @param $body
     * @return Item[]
     */
    public static function parse($body): array {
        $items = [];

        if (array_key_exists("items", $body)) {
            foreach ($body['items'] as $item) {
                $ppItem = new Item();
                $ppItem->setName($item['name']);
                $ppItem->setDesc($item['description']);
                $ppItem->setQuantity($item['quantity']);
                $ppItem->setUnitAmount($item['unit_amount']);
                $items[] = $ppItem;
            }
        }

        return $items;
    }

    /**
     * @param $data
     * @throws Exception
     */
    public static function validate($data) {
        foreach (self::$required_fields as $field) {
            if (!array_key_exists($field, $data)) {
                throw new Exception("Required Key Not Found");
            }
        }
    }

}