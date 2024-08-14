<?php


namespace Modules\Donation\Helpers;


class DonationHelpers
{
    public static function get_donation_charge($donation_amount,$symbol = false,$custom_tip = null)
    {
        if ($donation_amount && $donation_amount <1){
            return 0;
        }
        $allow_user_to_add_custom_tip_in_donation = get_static_option('allow_user_to_add_custom_tip_in_donation');
        $donation_charge_button_on = get_static_option('donation_charge_active_deactive_button');
        $charge_amount_type = get_static_option('charge_amount_type');
        $charge_amount = get_static_option('charge_amount');
        $donation_charge_form = get_static_option('donation_charge_form');
        $return_amount = 0;
        if ($donation_charge_form === 'campaign_owner'){
            return $return_amount;
        }
        if (!empty($allow_user_to_add_custom_tip_in_donation)){
            if (!is_null($custom_tip)){
                return $symbol ? amount_with_currency_symbol($custom_tip) : $custom_tip;
            }
        }


        if (!empty($donation_charge_button_on) && $charge_amount_type === 'percentage'){
            $return_amount = (int) $donation_amount * $charge_amount / 100;
        }elseif(!empty($donation_charge_button_on) && $charge_amount_type === 'amount'){
            $return_amount = $charge_amount;
        }

        return $symbol ? amount_with_currency_symbol($return_amount) : $return_amount;
    }

    public static function get_donation_total($amount,$symbol = false,$custom_tip = null){
        $return_amount = $amount + self::get_donation_charge($amount,false,$custom_tip);
        return $symbol ? amount_with_currency_symbol($return_amount) : $return_amount;
    }

    public static function get_donation_charge_for_campaign_owner($donation_amount,$symbol = false)
    {
        if ($donation_amount && $donation_amount <1){
            return 0;
        }
        $donation_charge_button_on = get_static_option('donation_charge_active_deactive_button');
        $charge_amount_type = get_static_option('charge_amount_type');
        $charge_amount = get_static_option('charge_amount');
        $donation_charge_form = get_static_option('donation_charge_form');
        $return_amount = 0;
        if ($donation_charge_form === 'user'){
            return $return_amount;
        }

        if (!empty($donation_charge_button_on) && $charge_amount_type === 'percentage'){
            $return_amount = (int) $donation_amount * $charge_amount / 100;
        }elseif(!empty($donation_charge_button_on) && $charge_amount_type === 'amount'){
            $return_amount = $charge_amount;
        }

        return $symbol ? amount_with_currency_symbol($return_amount) : $return_amount;
    }
}
