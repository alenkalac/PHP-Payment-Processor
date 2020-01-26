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
        //todo error handling
        $payment = new SellyPayment(
            $request->get("title"),
            $request->get("email"),
            $request->get("currency"),
            $request->get("value"),
            $request->get("gateway"),
            $request->get("returnURL"));

        //todo: add optional values
        $result = $this->sellyClient->createPayment($payment);

        //dd($result);
        return new Response($result->url);
    }
}