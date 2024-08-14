<?php

namespace App\Http\Services;

use App\Enums\PaymentRouteEnum;
use App\Helpers\FlashMsg;
use App\Helpers\Payment\PaymentGatewayCredential;
use App\Mail\BasicMail;
use App\Mail\BasicMailTwo;
use App\Mail\EventMail;
use App\Mail\StockOutEmail;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Modules\Campaign\Entities\CampaignSoldProduct;
use Modules\Event\Entities\EventPaymentLog;
use Modules\Product\Entities\OrderProducts;
use Modules\Product\Entities\ProductInventory;
use Modules\Product\Entities\ProductInventoryDetail;
use Modules\Product\Entities\ProductOrder;

class CheckoutToPaymentService
{
    public static function checkoutToGateway($data) // getting all parameter in one array
    {
        $payment_details = \Modules\Product\Entities\ProductOrder::find($data['order_log_id']);
        $payment_gateway = $payment_details->payment_gateway;
        $checkout_type = $payment_details->checkout_type;
        $amount_to_charge = $payment_details->total_amount;

        $ordered_products = \Modules\Product\Entities\OrderProducts::where('order_id', $payment_details->id)->get();
        foreach ($ordered_products ?? [] as $product)
        {
            if($product->campaign_product !== null)
            {
                $sold_count = CampaignSoldProduct::where('product_id', $product->product_id)->first();
                if (empty($sold_count))
                {
                    CampaignSoldProduct::create([
                        'product_id' => $product->product_id,
                        'sold_count' => 1,
                        'total_amount' => $product->campaign_product->campaign_price,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                } else {
                    if ($sold_count->sold_count < $product->campaign_product->units_for_sale)
                    {
                        if ($product->campaign_product->units_for_sale >= ($product->quantity + $sold_count->sold_count))
                        {
                            $sold_count->increment('sold_count', $product->quantity);
                            $sold_count->total_amount += $product->campaign_product->campaign_price*$product->quantity;
                            $sold_count->save();
                        } else {
                            return back()->withErrors('Campaign sell limitation is over, You can not purchase current amount');
                        }
                    } else {
                        return back()->withErrors('Campaign sell limitation is over, You can not purchase this product right now');
                    }
                }
            }

            if ($product->variant_id !== null)
            {
                $variants = ProductInventoryDetail::where(['product_id' => $product->product_id, 'id' => $product->variant_id])->get();
                if (!empty($variants))
                {
                    foreach ($variants ?? [] as $variant)
                    {
                        $variant->decrement('stock_count', $product->quantity);
                        $variant->increment('sold_count', $product->quantity);
                    }
                }
            }
            $product_inventory = ProductInventory::where('product_id', $product->product_id)->first();
            $product_inventory->decrement('stock_count', $product->quantity);
            $product_inventory->sold_count = $product_inventory->sold_count == null ? 1 : $product_inventory->sold_count + $product->quantity;
            $product_inventory->save();
        }

        self::checkStock(); // Checking Stock for warning and email notification

        if (!in_array($payment_gateway,['bank_transfer','manual_payment_']) && $checkout_type === 'digital') {
            $credential_function = 'get_' . $payment_gateway . '_credential';

            if (!method_exists((new PaymentGatewayCredential()), $credential_function))
            {
                $custom_data['request'] = $data;
                $custom_data['payment_details'] = $payment_details->toArray();
                $custom_data['total'] = $amount_to_charge;
                $custom_data['payment_type'] = "product_checkout";
                $custom_data['payment_for'] = "tenant";
                $custom_data['cancel_url'] = route(PaymentRouteEnum::CANCEL_ROUTE, random_int(111111,999999).$custom_data['payment_details']['id'].random_int(111111,999999));
                $custom_data['success_url'] = route(PaymentRouteEnum::SUCCESS_ROUTE, random_int(111111,999999).$custom_data['payment_details']['id'].random_int(111111,999999));

                $charge_customer_class_namespace = getChargeCustomerMethodNameByPaymentGatewayNameSpace($payment_gateway);
                $charge_customer_method_name = getChargeCustomerMethodNameByPaymentGatewayName($payment_gateway);

                $custom_charge_customer_class_object = new $charge_customer_class_namespace;
                if(class_exists($charge_customer_class_namespace) && method_exists($custom_charge_customer_class_object, $charge_customer_method_name))
                {
                    Cart::instance("default")->destroy();

                    return $custom_charge_customer_class_object->$charge_customer_method_name($custom_data);
                } else {
                    return back()->with(FlashMsg::explain('danger', 'Incorrect Class or Method'));
                }
            } else {
                $params = self::common_charge_customer_data($amount_to_charge, $payment_details, route('tenant.user.frontend.' . $payment_gateway . '.ipn'));
                try {
                    return PaymentGatewayCredential::$credential_function()->charge_customer($params);
                }catch (\Exception $e){
                    return back()->with(FlashMsg::explain('danger', $e->getMessage()));
                }
            }

        } else {
            $request = $data['validated_data'];
            $message = '';
            if ($payment_gateway == 'bank_transfer')
            {
                $fileName = time().'.'.$request['manual_payment_attachment']->extension();
                $request['manual_payment_attachment']->move('assets/uploads/attachment/',$fileName);

                ProductOrder::where('id', $payment_details->id)->update([
                    'status' => 'pending',
                    'payment_status' => 'pending',
                    'manual_payment_attachment' => $fileName
                ]);

                $customer_subject = __('Your payment sent and it is in admin approval stage..!').' '.get_static_option('site_'.get_user_lang().'_title');
                $admin_subject = __('You have a order with bank transfer, please check and approve..!').' '.get_static_option('site_'.get_user_lang().'_title');
                $message = __('New order added with bank transfer and it is now in admin approval stage..!');
            } else {
                ProductOrder::where('id', $payment_details->id)->update([
                    'status' => 'pending',
                    'payment_status' => 'pending',
                    'transaction_id' => $request['transaction_id'] ?? '',
                ]);

                $customer_subject = __('Your payment sent and it is in admin approval stage..!').' '.get_static_option('site_'.get_user_lang().'_title');
                $admin_subject = __('You have a new order with manual payment, please check and approve..!').' '.get_static_option('site_'.get_user_lang().'_title');
                $message = __('New order added with manual payment and it is now in admin approval stage..!');
            }


            try {
//                Mail::to(get_static_option('tenant_site_global_email'))->send(new BasicMail($message, $admin_subject,));
//                Mail::to($payment_details->email)->send(new BasicMail( $message, $customer_subject));

            } catch (\Exception $e) {

            }

            $order_id = Str::random(6) . $payment_details->id . Str::random(6);
            Cart::instance("default")->destroy();
            return redirect()->route(PaymentRouteEnum::SUCCESS_ROUTE, $order_id);
        }

        return redirect()->route('homepage');
    }

    private static function common_charge_customer_data($amount_to_charge, $payment_details, $ipn_url): array
    {
        $data = [
            'amount' => $amount_to_charge,
            'title' => 'Order ID: '.$payment_details->id,
            'description' => 'Payment For Order ID: #' . $payment_details->id .
            ' Payer Name: ' . $payment_details->name .
            ' Payer Email: ' . $payment_details->email,
            'order_id' => $payment_details->id,
            'track' => $payment_details->payment_track,
            'cancel_url' => route(PaymentRouteEnum::CANCEL_ROUTE, $payment_details->id),
            'success_url' => route(PaymentRouteEnum::SUCCESS_ROUTE, $payment_details->id),
            'email' => $payment_details->email,
            'name' => $payment_details->name,
            'payment_type' => 'order',
            'ipn_url' => $ipn_url,
        ];

        return $data;
    }

    private static function checkStock()
    {
        // Inventory Warnings
        $threshold_amount = get_static_option('stock_threshold_amount');

        $inventory_product_items = \Modules\Product\Entities\ProductInventoryDetail::where('stock_count', '<=', $threshold_amount)
            ->whereHas('is_inventory_warn_able', function ($query) {
                $query->where('is_inventory_warn_able', 1);
            })
            ->select('id', 'product_id')
            ->get();

        $inventory_product_items_id = !empty($inventory_product_items) ? $inventory_product_items->pluck('product_id')->toArray() : [];

        $products = \Modules\Product\Entities\Product::with('inventory')
            ->where('is_inventory_warn_able', 1)
            ->whereHas('inventory', function ($query) use ($threshold_amount) {
                $query->where('stock_count', '<=', $threshold_amount);
            })
            ->select('id')
            ->get();

        $products_id = !empty($products) ? $products->pluck('id')->toArray() : [];

        $every_filtered_product_id = array_unique(array_merge($inventory_product_items_id, $products_id));
        $all_products = \Modules\Product\Entities\Product::whereIn('id', $every_filtered_product_id)->select('id', 'name', 'is_inventory_warn_able')->get();

        if (count($all_products) > 0)
        {
            foreach ($all_products as $item)
            {
                $inventory = $item?->inventory?->stock_count;
                $variant = $item->inventoryDetail->where('stock_count', '<=', $threshold_amount)->first();
                $variant = !empty($variant) ? $variant->stock_count : [];

                $stock = min($inventory, $variant);
                $item->stock = $stock;
            }

            $email = get_static_option('order_receiving_email') ?? get_static_option('tenant_site_global_email');
            try {
                Mail::to($email)->send(new StockOutEmail($all_products));
            }catch (\Exception $e){

            }
        }
    }
}
