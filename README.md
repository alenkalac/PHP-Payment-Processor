## Payment Gateway MicroService

#### Supported 3rd Party Payment Services
* PayPal
* Stripe

API Guide

### Payment Processor
docker build -t docker.repik.co/alenkalac/payment-processor:1.3.3 -t docker.repik.co/alenkalac/payment-processor:latest -f Dockerfile .
docker push docker.repik.co/alenkalac/payment-processor:1.3.3
docker push docker.repik.co/alenkalac/payment-processor:latest