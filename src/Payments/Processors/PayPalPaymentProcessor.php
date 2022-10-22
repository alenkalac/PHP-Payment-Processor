<?php
namespace App\Payments\Processors;

use App\Payments\Cache\CustomCache;
use App\Payments\IPaymentProcessor;
use App\Payments\Items\RequestItemParser;
use App\Payments\Model\PayPalModel;
use App\Payments\PayPal\PayPalClient;
use Exception;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Cache\ItemInterface;

class PayPalPaymentProcessor implements IPaymentProcessor {

    private $PAYPAL_ACCESS_TOKEN_CACHE_KEY = "PAYPAL_ACCESS_TOKEN";

    /**
     * @var PayPalClient
     */
    private $client;


    public function __construct(PayPalClient $client) {
        $this->client = $client;

        $this->client->setSandboxMode($_ENV["PAYPAL_SANDBOX"]);
    }

    /**
     * @param Request $request
     * @return JsonResponse|Response
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function create(Request $request) {

        if(0 !== strpos($request->headers->get("Content-Type"), "application/json")) {
            return new Response("", 401);
        }

        $data = json_decode($request->getContent(), true);
        RequestItemParser::validate($data);

        //Build Order
        $orderBuilder = $this->client->getOrderBuilder()
            ->setAmount($data[PayPalClient::FIELD_AMOUNT])
            ->setCurrency($data[PayPalClient::FIELD_CURRENCY])
            ->setCancelUrl($data[PayPalClient::FIELD_CANCEL_URL])
            ->setReturnUrl($data[PayPalClient::FIELD_RETURN_URL]);

        $items = RequestItemParser::parse($data);
        $orderBuilder->addItems($items);

        //Execute Order against /v2/checkout/orders
        $data = $this->executeTransaction($this->client, $orderBuilder->build());
        return new JsonResponse($data);
    }


    /**
     * @throws InvalidArgumentException
     */
    public function complete(Request $request): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $payerId = $data[PayPalClient::FIELD_PAYER_ID];
        $orderId = $data[PayPalClient::FIELD_ORDER_ID];

        $transaction = $this->client->getPaymentBuilder()
            ->setPayerId($payerId)
            ->setOrderId($orderId)
            ->build();

        $data = $this->executeTransaction($this->client, $transaction);

        $model = new PayPalModel($data);
        return new JsonResponse($model->getResponseData());
    }

    /*
    public function refund(Request $request, PayPalClient $client, PayPalOrderRepository $orders, HttpClient $httpClient): JsonResponse {

        $transactionId = $request->get("tid", false);

        //find the paypal order
        $order = $orders->findOneBy([
            "status" => "COMPLETED",
            "transactionId" =>  $transactionId
        ]);

        $data = [];

        if($order) {
            $paypalRefund = new PayPalPaymentRefund($order->getTransactionId());
            $data = $this->executeTransaction($client, $paypalRefund);
        }

        if($data["status"] === "COMPLETED") {
            //TODO: Create a callback http client, but for now it's fine
            $order->setStatus("REFUNDED");

            $this->entityManager->persist($order);
            $this->entityManager->flush();

            $httpClient->post($order->getRefundCallback(), [
                "headers" => [
                    "content-type" => "application/json"
                ],
                "body" => json_encode($data)
            ]);
        }

        return new JsonResponse($data);

    }
    */


    /**
     * @throws Exception|InvalidArgumentException
     */
    private function getAccessToken(PayPalClient $paypalClient, $newToken = false) {
        $cache = new CustomCache();

        if($newToken) {
            $cache->delete($this->PAYPAL_ACCESS_TOKEN_CACHE_KEY);
        }

        return $cache->get($this->PAYPAL_ACCESS_TOKEN_CACHE_KEY, function (ItemInterface $item) use ($paypalClient) {
            $item->expiresAfter(3600);
            return $paypalClient->authenticate($_ENV["PAYPAL_CLIENT_ID"], $_ENV["PAYPAL_CLIENT_SECRET"]);
        });
    }

    /**
     * @throws InvalidArgumentException
     */
    private function executeTransaction(PayPalClient $client, $transaction) {

        $accessToken = $this->getAccessToken($client);

        try {
            $data = $client->execute($accessToken, $transaction);
        } catch (Exception $e) {
            $accessToken = $this->getAccessToken($client, true);
            $data = $client->execute($accessToken, $transaction);
        }

        return $data;
    }
}
