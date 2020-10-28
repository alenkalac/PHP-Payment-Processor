<?php
namespace App\Controller\PayPalControllers;

use App\Entity\PayPalOrder;
use App\Entity\ServerVariables;
use App\SymfonyPayments\Model\PayPalModel;
use App\SymfonyPayments\PayPal\Order\PayPalItem;
use App\SymfonyPayments\PayPal\PayPalClient;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PayPalCreatePaymentController extends AbstractController
{

    private $required_fields = [
        PayPalClient::FIELD_AMOUNT,
        PayPalClient::FIELD_CURRENCY,
        PayPalClient::FIELD_RETURN_URL,
        PayPalClient::FIELD_CANCEL_URL
    ];

    /**
     * @Route("/api/paypal/payment", methods={"POST", "OPTIONS"})
     * @param Request $request
     * @param PayPalClient $client
     * @return JsonResponse|void
     * @throws Exception
     */
    public function createPayPalPayment(Request $request, PayPalClient $client)
    {
        if (0 !== strpos($request->headers->get("Content-Type"), "application/json")) {
            return;
        }

        $data = json_decode($request->getContent(), true);
        $this->validate($data);

        //Build Order
        $orderBuilder = $client->getOrderBuilder()
            ->setAmount($data[PayPalClient::FIELD_AMOUNT])
            ->setCurrency($data[PayPalClient::FIELD_CURRENCY])
            ->setCancelUrl($data[PayPalClient::FIELD_CANCEL_URL])
            ->setReturnUrl($data[PayPalClient::FIELD_RETURN_URL]);

        if (array_key_exists("items", $data)) {
            foreach ($data['items'] as $item) {
                $ppItem = new PayPalItem();
                $ppItem->setName($item['name']);
                $ppItem->setDesc($item['description']);
                $ppItem->setQuantity($item['quantity']);
                $ppItem->setUnitAmount($item['unit_amount']);
                $orderBuilder->addItem($ppItem);
            }
        }

        //Execute Order against /v2/checkout/orders
        $data = $this->executeTransaction($client, $orderBuilder->build());
        return new JsonResponse($data);
    }

    /**
     * @Route("/api/paypal/payment", methods={"PUT", "GET"})
     * @param Request $request
     * @param PayPalClient $client
     * @return JsonResponse
     */
    public function completePayPalPayment(Request $request, PayPalClient $client)
    {
        $data = json_decode($request->getContent(), true);

        $payerId = $data[PayPalClient::FIELD_PAYER_ID];
        $orderId = $data[PayPalClient::FIELD_ORDER_ID];
        $refundCallback = null;

        if (key_exists("refund_callback", $data)) {
            $refundCallback = $data['refund_callback'];
        }

        $transaction = $client->getPaymentBuilder()
            ->setPayerId($payerId)
            ->setOrderId($orderId)
            ->build();

        $data = $this->executeTransaction($client, $transaction);

        $model = new PayPalModel($data);

        if ($refundCallback != null) {
            $ppOrder = new PayPalOrder();
            $ppOrder->setStatus($model->getStatus());
            $ppOrder->setOrderId($model->getOrderId());
            $ppOrder->setTransactionId($model->getTransactionId());
            $ppOrder->setRefundCallback($refundCallback);

            $this->getDoctrine()->getManager()->persist($ppOrder);
            $this->getDoctrine()->getManager()->flush();
        }

        return new JsonResponse($model->getResponseData());
    }

    private function getAccessToken($paypalClient, $newToken = false)
    {
        $repository = $this->getDoctrine()->getRepository(ServerVariables::class);

        $serverPayPalVariable = $repository->findOneBy([
            "property" => "PAYPAL_ACCESS_TOKEN"
        ]);

        $accessToken = null;

        if ($serverPayPalVariable == null || $newToken) {
            $accessToken = $paypalClient->authenticate($_ENV["PAYPAL_CLIENT_ID"], $_ENV["PAYPAL_CLIENT_SECRET"]);
            $serverPayPalVariable = $serverPayPalVariable == null ? new ServerVariables() : $serverPayPalVariable;
            $serverPayPalVariable->setProperty("PAYPAL_ACCESS_TOKEN");
            $serverPayPalVariable->setValue($accessToken);
            $this->getDoctrine()->getManager()->persist($serverPayPalVariable);
            $this->getDoctrine()->getManager()->flush();
        } else {
            $accessToken = $serverPayPalVariable->getValue();
        }
        return $accessToken;
    }

    private function executeTransaction(PayPalClient $client, $transaction)
    {
        $client->setSandboxMode($_ENV["PAYPAL_SANDBOX"]);
        $accessToken = $this->getAccessToken($client);

        try {
            $data = $client->execute($accessToken, $transaction);
        } catch (Exception $e) {
            $accessToken = $this->getAccessToken($client, true);
            $data = $client->execute($accessToken, $transaction);
        }

        return $data;
    }

    /**
     * @param $data
     * @throws Exception
     */
    private function validate($data)
    {
        foreach ($this->required_fields as $field) {
            if (!array_key_exists($field, $data)) {
                throw new Exception("Required Key Not Found");
            }
        }
    }
}