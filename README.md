## Payment Gateway MicroService

#### Supported 3rd Party Payment Services
* Selly
* Shoppy
* PayPal
* CashApp

API Guide

##### Create a payment  

```
GET /api/selly/payment
```
##### Params
| Field       | Required | Description                                       |
|:------------|:---------|:--------------------------------------------------|
| title       | true     | Product Title                                     |
| email       | true     | Customer's Email                                  |
| currency    | true     | Currency used to price the product                |
| value       | true     | Price of the product                              |
| gateway     | true     | Payment Gateway to use, options: PayPal, Bitcoin. |
| return_url  | true     | Where to redirect once payment is complete        |
| webhook_url | false    | Where to send a webhook response                  |

##### Successful Response
```
{
    "url": "https://selly.io/pay/81971eae19ff0924026d7b2a7502b20372c15df5/bGU3Q09QSGtDNjR2cHJMYzhHdTd6Mm40bXpFNVdZOEtlaW9NckRySmxsVkZOSjhkb3N0SVM0cVF6UDJtU0NjejVrT0Q4ZFZKY1JVbi9ZTjJaSDhGRXc9PS0tdW5zUGptYjcrSGZSRjF5K0VmNUFNZz09--68ab83743fa057629b09bb9c7330841e54442784"
}
```

##### Error Response
```
{
    "error": "Missing Required Fields"
}
```