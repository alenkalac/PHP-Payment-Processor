<?php

namespace App\Tests\Payments\PayPal\Order;

use App\Payments\Items\Item;
use App\Payments\PayPal\Order\PayPalOrderBuilder;
use PHPUnit\Framework\TestCase;

class PayPalOrderBuilderTest extends TestCase
{
    public function testBuildReturnsConfiguredOrder(): void
    {
        $item = new Item();
        $item->setName('Test Product');
        $item->setDesc('A test product');
        $item->setQuantity(1);
        $item->setUnitAmount(10.00);

        $builder = (new PayPalOrderBuilder())
            ->setAmount(10.00)
            ->setCurrency('USD')
            ->setReturnUrl('https://example.com/return')
            ->setCancelUrl('https://example.com/cancel');

        $builder->addItems([$item]);
        $order = $builder->build();

        self::assertSame(10.00, $order->getAmount());
        self::assertSame('USD', $order->getCurrency());
        self::assertSame('https://example.com/return', $order->getReturnUrl());
        self::assertSame('https://example.com/cancel', $order->getCancelUrl());
        self::assertCount(1, $order->getItems());

        $body = $order->getRequestBody();

        self::assertSame('CAPTURE', $body['intent']);
        self::assertSame('USD', $body['purchase_units'][0]['amount']['currency_code']);
        self::assertSame('Test Product', $body['purchase_units'][0]['items'][0]['name']);
    }
}
