<?php
/**
 * Created by PhpStorm.
 * User: Alen Kalac
 * Date: 26/01/2020
 * Time: 20:51
 */

namespace App\Controller\SellyControllers;

use App\SymfonyPayments\Selly\SellyClient;
use App\SymfonyPayments\Selly\SellyPayment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SellyCreatePaymentController extends AbstractController {
    private $sellyClient;

    public function __construct(SellyClient $client) {
        $this->sellyClient = $client;
        $this->sellyClient->auth($_ENV["SELLY_EMAIL"], $_ENV["SELLY_API"]);
    }

    /**
     * @Route("/api/selly/payment")
     * @param Request $request
     * @return mixed
     */
    public function createPayment(Request $request) {

        $title = $request->get("title", false);
        $email = $request->get("email", false);
        $currency = $request->get("currency", false);
        $value = $request->get("value", false);
        $gateway = $request->get("gateway", false);
        $returnURL = $request->get("returnURL", false);

        if(!$title || !$email || !$currency || !$value || !$gateway || !$returnURL) {
            return new Response(json_encode([
                "error" => "Missing Required Fields"
            ]));
        }

        $payment = new SellyPayment($title, $email, $currency, $value, $gateway, $returnURL);

        if($request->get("webhook_url", false)) {
            $payment->setWebhookUrl($request->get("webhook_url"));
        }

        $result = $this->sellyClient->createPayment($payment);

        return new Response($result->url);
    }
}