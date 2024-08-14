<?php

namespace App\Enums;

class PaymentRouteEnum
{
    const SUCCESS_ROUTE = 'tenant.user.frontend.order.payment.success';
    const CANCEL_ROUTE = 'tenant.user.frontend.order.payment.cancel';
    const STATIC_CANCEL_ROUTE = 'tenant.user.frontend.order.payment.cancel.static';
}
