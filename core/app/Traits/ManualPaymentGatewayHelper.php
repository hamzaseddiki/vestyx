<?php

namespace App\Traits;

trait ManualPaymentGatewayHelper
{
    public  function checkIsManualPayment(){
        return (bool) in_array(request()->selected_payment_gateway,['manual_payment','manual_payment_']);
    }
}
