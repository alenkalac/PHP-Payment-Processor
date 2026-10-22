<?php

namespace App\Tests\Payments\Items;

use App\Payments\Items\Item;
use App\Payments\Items\RequestItemParser;
use Exception;
use PHPUnit\Framework\TestCase;

class RequestItemParserTest extends TestCase
{
    public function testParseReturnsItems(): void
    {
        $items = RequestItemParser::parse([
            'items' => [
                [
                    'name' => 'Test Product',
                    'description' => 'A test product',
                    'quantity' => 2,
                    'unit_amount' => 9.99,
                ],
            ],
        ]);

        self::assertCount(1, $items);
        self::assertInstanceOf(Item::class, $items[0]);
        self::assertSame('Test Product', $items[0]->getName());
        self::assertSame('A test product', $items[0]->getDesc());
        self::assertSame(2, $items[0]->getQuantity());
        self::assertSame(9.99, $items[0]->getUnitAmount());
    }

    public function testParseWithoutItemsReturnsEmptyArray(): void
    {
        self::assertSame([], RequestItemParser::parse([]));
    }

    public function testValidateAcceptsRequiredFields(): void
    {
        RequestItemParser::validate([
            'amount' => 9.99,
            'currency' => 'USD',
            'return_url' => 'https://example.com/return',
            'cancel_url' => 'https://example.com/cancel',
        ]);

        self::assertTrue(true);
    }

    public function testValidateThrowsWhenFieldIsMissing(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Required Key Not Found');

        RequestItemParser::validate([
            'amount' => 9.99,
            'currency' => 'USD',
            'return_url' => 'https://example.com/return',
        ]);
    }
}
