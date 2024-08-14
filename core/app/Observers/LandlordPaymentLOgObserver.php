<?php

namespace App\Observers;

use App\Events\TenantRegisterEvent;
use App\Models\PaymentLogs;
use namespacetest\model\User;

class LandlordPaymentLOgObserver
{


    public function updated(PaymentLogs $paymentLog)
    {

    }


}
