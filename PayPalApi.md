# Intro To PayPal Payments

## How to integrate smart buttons with Symfony Payments

### Set Up
configure the following properties and restart the microservice.
```
PAYPAL_SANDBOX=true
PAYPAL_CLIENT_ID=
PAYPAL_CLIENT_SECRET=
```

### Front End

#### Front End Controllers/Routes
Download SymfonyPaymentsClient with composer
```
composer require alenkalac/symfony-payments-client
```

These examples are from a symfony project, and can be adapted to suit any language/framework.
```php

    private $paymentsClient;
    private $completePaymentClientBuilder;
    
    public function __construct() {
        $this->paymentsClient = new CreatePaymentBuilder("http://localhost:8777");
        $this->completePaymentClientBuilder = new CompletePaymentBuilder("http://localhost:8777");
    }

    /**
     * @Route("/checkout/create", methods={"POST"})
     */
    public function createPayment(Request $request) {
        $client = $this->paymentsClient;
        $data = $client->withAmount(2.99)
            ->withCurrency("USD")
            ->withReturnUrl("http://localhost/success")
            ->withCancelUrl("http://localhost/cancel")
            ->withPayPal() //or withStripe() for stripe.
            ->buildAndExecute();
        $data = json_decode($data);
        
        //At this point you'll have an orderId
        //save it to database with any additional info that you might want to
        //assign with this order, such as items or products/services.

        return new JsonResponse($data);
    }

    /**
     * @Route("/checkout/complete", methods={"POST"})
     */
    public function completeCheckout(Request $request) {
        $data = json_decode($request->getContent(), true);

        $payerId = $data["payerID"];
        $orderId = $data["orderID"];

        $response = $this->completePaymentClientBuilder
            ->forPayPal() //or forStripe($sessionId)
            ->withOrderId($orderId) //not needed for stripe
            ->withPayerId($payerId) //not needed for stripe
            ->buildAndExecute();
            
        if($response->getStatus() == "COMPLETE") {
            //handle successful payment.
            //query database for products associated with the orderId
            return new Response("");
        }

        return new Response("", 400);
    }
```

Web
```
<script src="https://www.paypalobjects.com/api/checkout.js"></script>
```

NPM
```
npm i paypal-checkout
```

Add an empty div with an id of paypal-button where you want the button to be displayed.
```
<div id="paypal-button"></div>
```

Attach a smart button script to the page
```javascript
paypal.Button.render({
        env: 'sandbox',
        style: {
            layout: 'horizontal',
            label: 'pay',
            fundingicons: 'true',
            size: 'large', //small, large, responsive
        },
        funding: {
            allowed: [ paypal.FUNDING.CARD ]
        },
        payment: (data, actions) => {
            return paypal.request({
                method: "POST",
                url: '/checkout/create', //server-sided route to create payment
            }).then(function(res) {
                return res.id;
            });
        },
        onAuthorize (data, actions) {
            //server-sided way to handle payment that is authorized.
            return fetch('/checkout/complete', {
                method: "POST",
                body: JSON.stringify({
                    orderID: data.orderID,
                    payerID: data.payerID
                })
            }).then(res => {
                if(!res.ok) {
                    throw Error(res.statusText);
                }
                return res;
            }).then(() => {
                //display success message or redirect, payment was successful
            }).catch(() => {
                //handle any errors that might have happened with the api call
            })
        },
        onError() {
            //Handle any errors that might have happened with PayPal in general
        },
        onCancel(data, actions) {
            //Handle canceled payments if you need to.
        }
    }, '#paypal-button');
```

