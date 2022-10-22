<?php

namespace App\SymfonyPayments;

use Symfony\Component\HttpFoundation\Request;

interface IPaymentProcessor {

    public function create(Request $request);
    public function complete(Request $request);

}