<?php

namespace App\Http\Requests;

use App\Http\Services\CheckoutCouponService;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Product\Entities\Product;
use stdClass;

class CheckoutFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $arr = [];
        if ($this->shift_another_address == 'on') {
            $arr = [
                'shift_name' => "required",
                'shift_phone' => "required",
                'shift_email' => "required|email",
                'shift_country' => "required|numeric",
                'shift_state' => "required|numeric",
                'shift_city' => "required",
                'shift_zipcode' => "required",
                'shift_address' => "required"
            ];
        } else {
            if (\Auth::guard('web')->user() == null) {
                $arr = [
                    'name' => "required",
                    'phone' => "required",
                    'email' => "required|email|unique:users,email",
                    'country' => "required|numeric",
                    'state' => "required|numeric",
                    'city' => "required",
                    'zipcode' => "required",
                    'address' => "required"
                ];

                $arr['create_accounts_input'] = 'nullable';
                if ($this->create_accounts_input != null)
                {
                    $arr['create_username'] = 'required';
                    $arr['create_password'] = 'required|same:create_password_confirmation|min:8';
                }
            } else {
                if(\Auth::guard('web')->user()->delivery_address == null)
                {
                    $arr = [
                        'name' => "required",
                        'phone' => "required",
                        'email' => "required|email|unique:users,email",
                        'country' => "required|numeric",
                        'state' => "required|numeric",
                        'city' => "required",
                        'zipcode' => "required",
                        'address' => "required"
                    ];
                }
            }
        }


        $arr['cash_on_delivery'] = 'nullable';
        if ($this->cash_on_delivery == null)
        {
            $arr['payment_gateway'] = 'required';
        }

        if ($this->payment_gateway == 'bank_transfer') {
            $arr['manual_payment_attachment'] = 'required';
        }

        if ($this->payment_gateway == 'manual_payment_') {
            $arr['transaction_id'] = 'required';
        }

        $arr['used_coupon'] = 'nullable';
        $arr['shipping_method'] = 'nullable';
        $arr['shift_another_address'] = 'nullable';
        $arr['message'] = 'nullable';
        return $arr + [
            "coupon" => "nullable",
            "coupon_discounted" => "nullable",
        ];
    }

    protected function prepareForValidation()
    {
        $request = new stdClass();
        $request->coupon = $this->used_coupon;
        $all_cart_items = Cart::content();
        $products = Product::with("category", "subCategory", "childCategory")->whereIn('id', $all_cart_items?->pluck("id")?->toArray())->get();
        $subtotal = Cart::subtotal();

        $discounted_value = CheckoutCouponService::calculateCoupon($request, $subtotal, $products, 'DISCOUNT');
        return $this->merge([
            "coupon" => $this->used_coupon,
            "coupon_discounted" => $discounted_value,
        ]);
    }

    public function messages()
    {
        return [
            'shift_name.required' => 'Name field is required.',
            'shift_phone.required' => 'Phone field is required.',
            'shift_email.email' => 'Email field must be valid email.',
            'shift_country.required' => 'Country field is required.',
            'shift_state.required' => 'State field is required.',
            'shift_city.required' => 'City field is required.',
            'shift_zipcode.required' => 'zipcode field is required.',

            'shift_address.required' => 'Address field is required.',

            'name.required' => 'Name field is required.',
            'phone.required' => 'Phone field is required.',
            'country.required' => 'Country field is required.',
            'state.required' => 'State field is required.',
            'city.required' => 'City field is required.',
            'address.required' => 'Address field is required.',

            'create_username.required' => 'Name field is required.',
            'create_password.required' => 'Password field is required.',
            'create_password.same' => 'Password and password confirmation must match.',

            'manual_trasaction_id.required' => 'Transaction ID is required.',
            'payment_gateway.required' => 'Payment Gateway is required.'
        ];
    }
}
