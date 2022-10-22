<?php

namespace App\Controller;

use App\Payments\PaymentProcessors;
use App\Payments\Processors\PaymentProcessorNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController {

    /**
     * @Route("/api/payment/{processor}", methods={"POST", "OPTIONS"})
     * @param Request $request
     * @param PaymentProcessors $paymentProcessors
     * @return mixed
     * @throws PaymentProcessorNotFoundException
     */
    public function createPayment(Request $request, PaymentProcessors $paymentProcessors) {
        $processor = $request->get("processor");

        return $paymentProcessors->get($processor)->create($request);
    }

    /**
     * @Route("/api/payment/{processor}", methods={"PUT"})
     * @param Request $request
     * @param PaymentProcessors $paymentProcessors
     * @return mixed
     * @throws PaymentProcessorNotFoundException
     */
    public function completePayment(Request $request, PaymentProcessors $paymentProcessors) {
        $processor = $request->get("processor");

        return $paymentProcessors->get($processor)->complete($request);
    }

}