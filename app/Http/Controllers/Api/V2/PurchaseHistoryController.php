<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\PurchaseHistoryMiniCollection;
use App\Http\Resources\V2\PurchaseHistoryCollection;
use App\Http\Resources\V2\PurchaseHistoryItemsCollection;
use App\Http\Resources\V2\PurchaseNuxtHistoryItemsCollection;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PurchaseHistoryController extends Controller
{
    public function index(Request $request)
    {
        $order_query = Order::query();
        if ($request->payment_status != "" || $request->payment_status != null) {
            $order_query->where('payment_status', $request->payment_status);
        }
        if ($request->delivery_status != "" || $request->delivery_status != null) {
            $delivery_status = $request->delivery_status;
            $order_query->whereIn("id", function ($query) use ($delivery_status) {
                $query->select('order_id')
                    ->from('order_details')
                    ->where('delivery_status', $delivery_status);
            });
        }
        return new PurchaseHistoryMiniCollection($order_query->where('user_id', auth()->user()->id)->latest()->paginate(5));

    }

    public function details($id)
    {
        $order_detail = Order::where('id', $id)->where('user_id', auth()->user()->id)->get();
        return new PurchaseHistoryCollection($order_detail);
    }
    public function orderDetails($id)
    {
        $order_detail = Order::where('id', $id)->first();
        
        $order_detail_new=$this->formatOrder($order_detail);

        $order_detail_new['products']= new PurchaseNuxtHistoryItemsCollection(OrderDetail::where('order_id', $id)->get());

        return response()->json($order_detail_new);
    }

    public function items($id)
    {
        $order_id = Order::select('id')->where('id', $id)->where('user_id', auth()->user()->id)->first();
        $order_query = OrderDetail::where('order_id', $order_id->id);
        return new PurchaseHistoryItemsCollection($order_query->get());
    }

    public function formatOrder($data)
    {
        return  [   'id' => $data->id,
                    'code' => $data->code,
                    'user_id' => (int) $data->user_id,
                    'shipping_address' => json_decode($data->shipping_address),
                    'payment_type' => ucwords(str_replace('_', ' ', $data->payment_type)),
                    'shipping_type' => $data->shipping_type,
                    'shipping_type_string' => $data->shipping_type != null ? ucwords(str_replace('_', ' ', $data->shipping_type)) : "",
                    'payment_status' => $data->payment_status,
                    'payment_status_string' => ucwords(str_replace('_', ' ', $data->payment_status)),
                    'delivery_status' => $data->delivery_status,
                    'delivery_status_string' => $data->delivery_status == 'pending'? "Order Placed" : ucwords(str_replace('_', ' ',  $data->delivery_status)),
                    'grand_total' => $data->grand_total,
                    'coupon_discount' => $data->coupon_discount,
                    'shipping_cost' => $data->orderDetails->sum('shipping_cost'),
                    'subtotal' => $data->orderDetails->sum('price'),
                    'tax' => format_price($data->orderDetails->sum('tax')),
                    'date' => Carbon::createFromTimestamp($data->date)->format('d-m-Y'),
                    'cancel_request' => $data->cancel_request == 1,
                    'manually_payable' => $data->manual_payment && $data->manual_payment_data == null,
                    'links' => [
                        'details' => ''
                    ]
                ];
    }
}
