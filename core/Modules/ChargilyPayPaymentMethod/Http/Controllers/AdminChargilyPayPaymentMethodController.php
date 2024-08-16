<?php

namespace Modules\ChargilyPayPaymentMethod\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AdminChargilyPayPaymentMethodController extends Controller
{
    public function chargeCustomer($args)
    {
        // $args has the payment details along with request information
        // Your code
        dd($args);
    }
}
