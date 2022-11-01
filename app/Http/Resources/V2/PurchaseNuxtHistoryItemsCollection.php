<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PurchaseNuxtHistoryItemsCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map(function ($data) {

                $refund_section = false;
                $refund_button = false;
                $refund_label = "";
                $refund_request_status = 99;
                return [
                    'id' => $data->id,
                    'product_id' => $data->product->id,
                    'product_name' => $data->product->name,
                    'small_pictures'=>formatSmallPictures($data->product->thumbnail_img, $data->product->photos, $data->product->id),
                    'variation' => $data->variation,
                    'price' => $data->price,
                    'tax' => format_price($data->tax),
                    'shipping_cost' => $data->shipping_cost,
                    'coupon_discount' => $data->coupon_discount,
                    'quantity' => (int)$data->quantity,
                    'payment_status' => $data->payment_status,
                    'payment_status_string' => ucwords(str_replace('_', ' ', $data->payment_status)),
                    'delivery_status' => $data->delivery_status,
                    'delivery_status_string' => $data->delivery_status == 'pending' ? "Order Placed" : ucwords(str_replace('_', ' ', $data->delivery_status)),
                    'refund_section' => $refund_section,
                    'refund_button' => $refund_button,
                    'refund_label' => $refund_label,
                    'refund_request_status' => $refund_request_status,
                ];
            });
    }

    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }
}
