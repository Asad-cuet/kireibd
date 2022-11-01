@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <h1 class="mb-0 h6">{{ translate('Edit Product') }}</h5>
</div>
<div class="">
    <form class="form form-horizontal mar-top" action="{{ route('all_orders.update', $order->id) }}" method="POST" enctype="multipart/form-data" id="choice_form">
        <div class="row gutters-5">
        <div class="col-lg-12">
                <!-- <input name="_method" type="hidden" value="POST">
                <input type="hidden" name="id" value="{{ $order->id }}"> -->
                @csrf
                <div class="card">
                    <ul class="nav nav-tabs nav-fill border-light">

                        <li class="nav-item">
                            <span>{{translate('Order')}}</span>
                        </li>

                    </ul>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-lg-3 col-from-label">{{translate('Customer Name')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" name="name" placeholder="{{translate('Customer Name')}}" value="{{ $order->user->name }}">
                            </div>
                        </div>
                        <div class="form-group row" id="category">
                            <label class="col-lg-3 col-from-label">{{translate('Category')}}</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" name="shipping_type" placeholder="{{translate('Shipping Type')}}" value="{{ $order->shipping_type }}">
                            </div>
                        </div>
                        <div class="form-group row" id="pickup_point">
                            <label class="col-lg-3 col-from-label">{{translate('Pickup Point')}}</label>
                            <div class="col-lg-8">
                                <select class="form-control aiz-selectpicker" name="pickup_point_id" id="pickup_point_id" data-live-search="true">
                                    <option value="">{{ translate('Select Pickup Point') }}</option>
                                    @foreach (\App\Models\PickupPoint::all() as $pickup)
                                    <option value="{{ $pickup->id }}" {{ $pickup->id == $order->pickup_point_id ? 'selected' : '' }}>{{ $pickup->getTranslation('name') }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-from-label">{{translate('Delivery Status')}}</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" name="delivery_status" id="delivery_status" value="{{ $order->delivery_status }}" placeholder="{{ translate('Delivery Status') }}" data-role="deliveryinput">
                            </div>
                        </div>

                        <div class="form-group row" id="payment_type">
                            <label class="col-md-3 col-from-label">{{translate('Payment Type')}}</label>
                            <div class="col-md-8">
                                <select class="form-control aiz-selectpicker" name="payment_type" id="payment_type" data-live-search="true" required>
                                    <option value="" selected>{{ translate('Select Payment Type') }}</option>
                                    <option value="cash_on_delivery" {{ $order->payment_type == 'cash_on_delivery' ? 'selected' : '' }}>Cash on delivery</option>
                                    <option value="bkash" {{ $order->payment_type == 'bkash' ? 'selected' : '' }}>Bkash</option>
                                    <option value="ssl" {{ $order->payment_type == 'ssl' ? 'selected' : '' }}>SSL</option>

                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Date')}}</label>
                            <div class="col-md-8">
                                <input type="datetime-local" class="form-control" name="date" id="date" value="{{ Carbon\Carbon::parse($order['created_at']) }}" placeholder="{{ translate('Date') }}">
                            </div>
                        </div>
                        <!-- <div class="form-group row mb-3">
                            <label class="col-sm-3 control-label" for="products">{{translate('Products')}}</label>
                            <div class="col-md-8">
                                <select name="products[]" id="products" class="form-control aiz-selectpicker" multiple required data-placeholder="{{ translate('Choose Products') }}" data-live-search="true" data-selected-text-format="count">
                                    @foreach(\App\Models\Product::orderBy('created_at', 'desc')->get() as $product)
                                        <option value="{{$product->id}}" @foreach($order->orderDetails as $orDetails) {{ $product->id == $orDetails->product_id ? 'selected' : '' }} @endforeach >{{ $product->getTranslation('name') }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> -->
                    </div>
                    <!-- <div class="row gutters-5">
                        <div class="col-lg-12">
                            <div class="alert alert-danger">
                                {{ translate('If any product has discount or exists in another flash deal, the discount will be replaced by this discount & time limit.') }}
                            </div>
                            <br>
                        </div>
                    </div> -->

                </div>
            </div>
            <!-- <div class="card">
                <div class="row gutters-5">
                    <div class="col-lg-12">
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{translate('Shipping Name')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="name" placeholder="{{translate('Shipping Name')}}" value="{{ $order->user->name }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{translate('Shipping Email')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="name" placeholder="{{translate('Shipping Email')}}" value="{{ $order->user->email }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{translate('Shipping Phone')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="name" placeholder="{{translate('Shipping Phone')}}" value="{{ $order->user->phone }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{translate('Shipping District')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                <div class="col-md-8">
                                <select class="form-control aiz-selectpicker" name="shipping_state" id="shipping_state" data-live-search="true">
                                    <option value="">{{ translate('Select District') }}</option>
                                    @foreach (\App\Models\State::all() as $state)
                                    <option value="{{ $state->id }}" {{ $state->id == $order->user->state ? 'selected' : '' }}>{{ $state->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{translate('Shipping Area')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                                <div class="col-md-8">
                                <select class="form-control aiz-selectpicker" name="shipping_city" id="city_id" data-live-search="true">
                                    <option value="">{{ translate('Select Area') }}</option>
                                    @foreach (\App\Models\City::all() as $city)
                                    <option value="{{ $city->id }}" {{ $city->id == $order->user->city ? 'selected' : '' }}>{{ $city->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->



                <div class="col-lg-12">
                <div class="card">

                    <div class="form-group" id="discount_table">

                    </div>

                    <div class="form-group"><table class="table table-bordered aiz-table footable footable-1 breakpoint-xl">
                        <tr class="footable-header">
                            <td style="display: table-cell;" class="footable-first-visible" width="50%">
                                <span>Product</span>
                                </td>
                                <td data-breakpoints="lg" style="display: table-cell;" width="20%">
                                <span>Base Price</span>
                                </td>
                                <td data-breakpoints="lg" style="display: table-cell;" class="footable-last-visible" width="10%">
                                <span>Quantity</span>
                            </td>
                        </tr>
            	          
            	        @foreach($order->orderDetails as $details)
                            <tr>
                                <td>{{ $details->product->name }}</td>
                                <td>{{ $details->product->unit_price }}</td>
                                <td>{{ $details->quantity }}</td>
                            </tr>
                        @endforeach
                        </table>
                                
                    </div>
                    <div class="col-12">
                        <div class="mb-3 text-right">
                            <button type="submit" name="button" class="btn btn-info">{{ translate('Update Order') }}</button>
                        </div>
                    </div>
                </div>
            </div>


          
            
        </div>
    </form>
</div>

@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function(){
            $('#products').on('change', function(){
                var product_ids = $('#products').val();
                if(product_ids.length > 0){
                    $.post('{{ route('flash_deals.product_discount') }}', {_token:'{{ csrf_token() }}', product_ids:product_ids}, function(data){
                        $('#discount_table').html(data);
                        AIZ.plugins.fooTable();
                    });
                }
                else{
                    $('#discount_table').html(null);
                }
            });
        });
    </script>
@endsection
