<?php

use App\Facades\ModuleDataFacade;
use Modules\PluginManage\Http\Helpers\PluginJsonFileHelper;

function getAllExternalMenu()
{
    return ModuleDataFacade::getAllExternalMenu();
}

function getAllExternalPaymentGatewayMenu()
{
    return ModuleDataFacade::getAllExternalPaymentGatewayMenu();
}

function getExternalPaymentGateway()
{
    return ModuleDataFacade::getExternalPaymentGateway();
}

function getAllPaymentGatewayList()
{
    return ModuleDataFacade::getAllPaymentGatewayList();
}

function getAllPaymentGatewayListWithImage()
{
    return ModuleDataFacade::getAllPaymentGatewayListWithImage();
}

/**
 * @param $imageName
 * @param $moduleName
 * @return string
 */
function renderPaymentGatewayImage($imageName, $moduleName): string
{
    return ModuleDataFacade::renderPaymentGatewayImage($imageName, $moduleName);
}

function getPaymentGatewayImagePath($gateway_slug)
{
    return ModuleDataFacade::getPaymentGatewayImagePath($gateway_slug);
}

function renderAllPaymentGatewayExtraInfoBlade()
{
    return ModuleDataFacade::renderAllPaymentGatewayExtraInfoBlade();
}

/**
 * @param $payment_gateway_name
 * @return mixed
 */
function getChargeCustomerMethodNameByPaymentGatewayNameSpace($payment_gateway_name): mixed
{
    return ModuleDataFacade::getChargeCustomerMethodNameByPaymentGatewayNameSpace($payment_gateway_name);
}

/**
 * @param $payment_gateway_name
 * @return mixed
 */
function getChargeCustomerMethodNameByPaymentGatewayName($payment_gateway_name): mixed
{
    return ModuleDataFacade::getChargeCustomerMethodNameByPaymentGatewayName($payment_gateway_name);
}


function loadPaymentGatewayLogo($moduleName, $gatewayName)
{
    return route('payment.gateway.logo', [$moduleName, $gatewayName]);
}

function isPluginActive($moduleName)
{

    return (new PluginJsonFileHelper($moduleName))->isPluginActive();
}

function module_assets($module_name, $path, $custom_directory = ''){
    $destination = global_asset(module_dir($module_name).'resources/assets/'.$path);
    if (!empty($custom))
    {
        $destination = global_asset(module_dir($module_name).$custom_directory);
    }

    return $destination;
}
