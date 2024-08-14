<?php

namespace App\Enums;

use ReflectionClass;

class PricePlanTypEnums
{
    const MONTHLY = 0;
    const YEARLY = 1;
    const LIFETIME = 2;
    const CUSTOM = 3;

    public static function getText(int $const)
    {
        foreach (self::getPricePlanTypeList() as $index => $item) {
            if ($const == $index) {
                return __(ucwords(strtolower($item)));
            }
        }
    }

    private static function getAttributes(): array
    {
        $reflect = new ReflectionClass(__CLASS__);
        return $reflect->getConstants() ?? [];
    }

    public static function getPricePlanTypeList(): array
    {
        $valueArr = [];
        foreach (self::getAttributes() as $index => $attribute) {
            $valueArr[$attribute] = __(ucwords(strtolower($index)));
        }

        return $valueArr;
    }

    public static function getFeatureList()
    {
        $all_features = [];
        foreach (self::features() ?? [] as $item)
        {
            $all_features[$item] = __($item);
        }

        if (moduleExists('HotelBooking'))
        {
            $all_features['hotel_booking'] = __('hotel_booking');
        }
        if (moduleExists('Restaurant'))
        {
            $all_features['restaurant'] = __('restaurant');
        }

        return $all_features;
    }

    private static function features()
    {
        return ['dashboard','admin','user','brand','newsletter','custom_domain','testimonial','form_builder','own_order_manage',
            'page','blog','service','donation','job','appointment','event','support_ticket','knowledgebase','faq','gallery','video','portfolio','eCommerce',
            'storage','advertisement','wedding_price_plan','appearance_settings','general_settings','language','payment_gateways','themes'];
    }
}
