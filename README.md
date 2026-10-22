## Payment Gateway MicroService

#### Supported 3rd Party Payment Services
* PayPal
* Stripe

API Guide

### Payment Processor
docker build -t docker.noxyproxy.com/alenkalac/payment-processor:1.3.4 -t docker.noxyproxy.com/alenkalac/payment-processor:latest -f Dockerfile .
docker push docker.noxyproxy.com/alenkalac/payment-processor:1.3.4
docker push docker.noxyproxy.com/alenkalac/payment-processor:latest