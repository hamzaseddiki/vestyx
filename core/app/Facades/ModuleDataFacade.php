<?php

namespace App\Facades;

use App\Helpers\ModuleMetaData;
use Illuminate\Support\Facades\Facade;


/**
 * @see ModuleMetaData
 * @method static getPageBuilderAddonList
 * @method static getWidgetBuilderAddonList
 * @method static getAllExternalMenu
 * @method static getExternalPaymentGateway
 * @method static getAllPaymentGatewayList
 * @method static renderAllPaymentGatewayExtraInfoBlade
 * @method static getAllPaymentGatewayListWithImage
 * @method static renderPaymentGatewayImage($imageName, $moduleName)
 * @method static getChargeCustomerMethodNameByPaymentGatewayName
 * @method static getChargeCustomerMethodNameByPaymentGatewayNameSpace
 * */
class ModuleDataFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ModuleDataFacade';
    }
}
