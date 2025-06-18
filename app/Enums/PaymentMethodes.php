<?php

namespace App\Enums;

enum PaymentMethodes : string
{
    case Stripe = 'stripe';
    case Paypal = 'paypal';
    case Paytm = 'paytm';
    case Paymob = 'paymob';
    case Cash = 'cash';

}
