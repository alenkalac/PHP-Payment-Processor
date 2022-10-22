<?php
namespace App\Controller\PayPalControllers;

use App\Entity\PayPalOrder;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PayPalWebhookController extends AbstractController {

    /**
     * @Route("/webhook/paypal", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function payPalWebhook(Request $request): Response {

        $httpClient = new Client();
        $data = json_decode($request->getContent());

        $eventType = $data->event_type;

        if($eventType == "CUSTOMER.DISPUTE.CREATED") {
            $transactionId = $data->resource->disputed_transactions[0]->seller_transaction_id;

            $repo = $this->getDoctrine()->getRepository(PayPalOrder::class);
            $order = $repo->findOneBy([
                "transactionId" => $transactionId
            ]);

            if($order != null) {
                $callback = $order->getRefundCallback();
                $httpClient->post($callback, [
                    "body" => json_encode([
                        "action" => "DISPUTE-CREATED",
                        "status" => "CREATED",
                        "transactionId" => $transactionId
                    ])
                ]);
                $order->setStatus("DISPUTE");
            }
        } else if ($eventType == "CUSTOMER.DISPUTE.RESOLVED") {
            $transactionId = $data->resource->disputed_transactions[0]->seller_transaction_id;
            $outcome = $data->resource->dispute_outcome->outcome_code;

            $repo = $this->getDoctrine()->getRepository(PayPalOrder::class);
            $order = $repo->findOneBy([
                "transactionId" => $transactionId
            ]);

            if($order != null) {
                $callback = $order->getRefundCallback();
                $httpClient->post($callback, [
                    "body" => json_encode([
                        "action" => "DISPUTE-RESOLVED",
                        "status" => $outcome,
                        "transactionId" => $transactionId
                    ])
                ]);
            }
        }

        return new Response();
    }
}