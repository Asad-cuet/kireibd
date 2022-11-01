@extends('backend.layouts.app')

@section('content')

@php
    
    
@endphp

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Add New Order')}}</h5>
</div>
<div class="">
    <form class="form form-horizontal mar-top" action="{{route('new_order_store')}}" method="POST" enctype="multipart/form-data" id="choice_form">
        <div class="row gutters-5">
            <div class="col-lg-12">
                @csrf
                <input type="hidden" name="added_by" value="admin">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Order Information')}}</h5>
                    </div>
                    <div class="card-body">
                    <div class="form-group row" id="user">
                            <label class="col-md-3 col-from-label">{{translate('Customer')}}</label>
                            <div class="col-md-8">
                                <select class="form-control aiz-selectpicker" name="user_id" id="user_id" data-live-search="true">
                                    <option value="">{{ translate('Select Customer') }}</option>
                                    @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Shipping Name')}} <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="shipping_name" placeholder="{{ translate('Shipping Name') }}" required>
                            </div>
                        </div>


                        
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Shipping Phone')}}</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="shipping_phone" placeholder="{{translate('Shipping Phone')}}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Shipping Address')}}</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="shipping_address" placeholder="{{ translate('Shipping Address') }}">
                            </div>
                        </div>

                        <div class="form-group row" id="payment_type">
                            <label class="col-md-3 col-from-label">{{translate('Payment Type')}}</label>
                            <div class="col-md-8">
                                <select class="form-control aiz-selectpicker" name="payment_type" id="payment_type" data-live-search="true" required>
                                    <option value="" selected>{{ translate('Select Payment Type') }}</option>
                                    <option value="cash_on_delivery">Cash on delivery</option>
                                    <option value="bkash">Bkash</option>
                                    <option value="ssl">SSL</option>

                                </select>
                            </div>
                        </div>
                        <div class="form-group row" id="state">
                            <label class="col-md-3 col-from-label">{{translate('District')}}</label>
                            <div class="col-md-8">
                                <select class="form-control aiz-selectpicker" name="shipping_state" id="shipping_state" data-live-search="true">
                                    <option value="">{{ translate('Select District') }}</option>
                                    @foreach (\App\Models\State::all() as $state)
                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row" id="city">
                            <label class="col-md-3 col-from-label">{{translate('Area')}}</label>
                            <div class="col-md-8">
                                <select class="form-control aiz-selectpicker" name="shipping_city" id="city_id" data-live-search="true">
                                    <option value="">{{ translate('Select City') }}</option>
                                    @foreach (\App\Models\City::all() as $city)
                                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label class="col-sm-3 control-label" for="products">{{translate('Products')}}</label>
                            <div class="col-sm-9">
                                <select name="products[]" id="products" class="form-control aiz-selectpicker" multiple required data-placeholder="{{ translate('Choose Products') }}" data-live-search="true" data-selected-text-format="count">
                                    @foreach(\App\Models\Product::orderBy('created_at', 'desc')->get() as $product)
                                        <option value="{{$product->id}}">{{ $product->getTranslation('name') }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="alert alert-danger">
                        {{ translate('If any product has discount or exists in another flash deal, the discount will be replaced by this discount & time limit.') }}
                    </div>
                    <br>
                    
                    <div class="form-group" id="item_table">

                    </div>


                    </div>
                </div>


            </div>

            <div class="col-12">
                <div class="btn-toolbar float-right mb-3" role="toolbar" aria-label="Toolbar with button groups">
                    
                    <div class="btn-group" role="group" aria-label="Second group">
                        <button type="submit" name="button" value="publish" class="btn btn-success action-btn">{{ translate('Save & Publish') }}</button>
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

        syncProducts();

        function syncProducts(){
            var product_ids = $('#products').val();
            if(product_ids.length > 0){
                $.post("{{ route('add_new_order.product_filter') }}", {_token:'{{ csrf_token() }}', product_ids:product_ids}, function(data){
                    $('#item_table').html(data);
                    AIZ.plugins.fooTable();
                });
            }
            else{
                $('#item_table').html(null);
            }
        }


        $('#products').on('change', function(){
            syncProducts();
        });
    });
</script>
@endsection
