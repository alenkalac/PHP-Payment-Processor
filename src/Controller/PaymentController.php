<?php

namespace App\Controller;

use App\Payments\PaymentProcessors;
use App\Payments\Processors\PaymentProcessorNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class PaymentController extends AbstractController {

    /**
     * @throws PaymentProcessorNotFoundException
     */
    #[Route('/api/payment/{processor}', methods: ['POST', 'OPTIONS'])]
    public function createPayment(Request $request, PaymentProcessors $paymentProcessors) {
        $processor = $request->get("processor");

        return $paymentProcessors->get($processor)->create($request);
    }

    /**
     * @throws PaymentProcessorNotFoundException
     */
    #[Route('/api/payment/{processor}', methods: ['PUT'])]
    public function completePayment(Request $request, PaymentProcessors $paymentProcessors) {
        $processor = $request->get("processor");

        return $paymentProcessors->get($processor)->complete($request);
    }

}