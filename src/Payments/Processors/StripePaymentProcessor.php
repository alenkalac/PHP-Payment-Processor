<?php

namespace App\Payments\Processors;

use App\Payments\IPaymentProcessor;
use App\Payments\Items\RequestItemParser;
use App\Payments\PaymentFields;
use Exception;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Stripe\StripeClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class StripePaymentProcessor implements IPaymentProcessor {

    /**
     * @var StripeClient
     */
    private $client;

    public function __construct() {
        Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
    }

    /**
     * @throws ApiErrorException
     * @throws Exception
     */
    public function create(Request $request): JsonResponse {
        $data = json_decode($request->getContent(), true);

        RequestItemParser::validate($data);

        $currency = $data[PaymentFields::FIELD_CURRENCY];
        $items = $this->processItems($data, $currency);

        $curl = $data[PaymentFields::FIELD_CANCEL_URL];
        $surl = $data[PaymentFields::FIELD_RETURN_URL];
        $description = key_exists("description", $data) ? $data["description"] : "";

        $session = Session::create([
            'line_items' => $items,
            'mode' => 'payment',
            'success_url' => $surl,
            'cancel_url' => $curl,
            'payment_intent_data' => [
                'description' => $description
            ]
        ]);

        return new JsonResponse($session->toArray());
    }

    private function processItems($data, $currency): array {
        $items = RequestItemParser::parse($data);

        $lineItems = [];
        foreach ($items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => $currency,
                    'product_data' => [
                        'name' => $item->getName(),
                        'description' => $item->getDesc()
                    ],
                    'unit_amount' => $item->getUnitAmount() * 100,
                ],
                'quantity' => $item->getQuantity(),
            ];
        }

        return $lineItems;
    }

    /**
     * @throws ApiErrorException
     */
    public function complete(Request $request): JsonResponse {
        $data = json_decode($request->getContent(), false);

        $payment = Session::retrieve($data->sessionId);

        return new JsonResponse($payment->toArray());
    }
}