@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <h1 class="mb-0 h6">{{ translate('Edit Product') }}</h5>
</div>
<div class="">
    <form class="form form-horizontal mar-top" action="{{route('products.update', $product->id)}}" method="POST" enctype="multipart/form-data" id="choice_form">
        <div class="row gutters-5">
            <div class="col-lg-8">
                <input name="_method" type="hidden" value="POST">
                <input type="hidden" name="id" value="{{ $product->id }}">
                <input type="hidden" name="lang" value="{{ $lang }}">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-lg-3 col-from-label">{{translate('Product Name')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" name="name" placeholder="{{translate('Product Name')}}" value="{{ $product->getTranslation('name', $lang) }}" required>
                            </div>
                        </div>
                        <div class="form-group row" id="category">
                            <label class="col-lg-3 col-from-label">{{translate('Categories')}}</label>
                            <div class="col-lg-8">
                                <select class="form-control aiz-selectpicker" name="categories[]" id="categories" data-live-search="true" multiple required>
                                    @foreach ($categories as $category)
                                    <option 
                                    @foreach($product->categories as $productCategory) 
                                    {{ $category->id == $productCategory->id ? 'selected' : '' }} 
                                    @endforeach 
                                    value="{{ $category->id }}">{{ $category->getTranslation('name') }}</option>
                                    {{-- @foreach ($category->childrenCategories as $childCategory)
                                    @include('categories.child_category', ['child_category' => $childCategory])
                                    @endforeach --}}
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row" id="brand">
                            <label class="col-lg-3 col-from-label">{{translate('Brand')}}</label>
                            <div class="col-lg-8">
                                <select class="form-control aiz-selectpicker" name="brand_id" id="brand_id" data-live-search="true">
                                    <option value="">{{ translate('Select Brand') }}</option>
                                    @foreach (\App\Models\Brand::all() as $brand)
                                    <option value="{{ $brand->id }}" @if($product->brand_id == $brand->id) selected @endif>{{ $brand->getTranslation('name') }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- <div class="form-group row">
                            <label class="col-lg-3 col-from-label">{{translate('Unit')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i> </label>
                            <div class="col-lg-8"> -->
                                <input type="hidden" class="form-control" name="unit" placeholder="{{ translate('Unit (e.g. KG, Pc etc)') }}" value="{{$product->getTranslation('unit', $lang)}}" hidden>
                            <!-- </div>
                        </div> -->
                        <!-- <div class="form-group row">
                            <label class="col-lg-3 col-from-label">{{translate('Minimum Purchase Qty')}}</label>
                            <div class="col-lg-8"> -->
                                <input type="hidden" lang="en" class="form-control" name="min_qty" value="@if($product->min_qty <= 1){{1}}@else{{$product->min_qty}}@endif" min="1">
                            <!-- </div>
                        </div> -->
                        <div class="form-group row">
                            <label class="col-lg-3 col-from-label">{{translate('Tags')}}</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control aiz-tag-input" name="tags[]" id="tags" value="{{ $product->tags }}" placeholder="{{ translate('Type to add a tag') }}" data-role="tagsinput">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Key Ingredients')}}</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control aiz-tag-input" name="key_ingredients[]" id="key_ingredients" value="{{ $product->key_ingredients }}" placeholder="{{ translate('Type and hit enter to add a key ingredient') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Skin Types')}}</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control aiz-tag-input" name="skin_types[]" id="skin_types" value="{{ $product->skin_types }}" placeholder="{{ translate('Type and hit enter to add a skin type') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Good For')}}</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control aiz-tag-input" name="good_for[]" id="good_for" value="{{ $product->good_for }}" placeholder="{{ translate('Type and hit enter to add a good for') }}">
                            </div>
                        </div>



                        
                        <!-- @if (addon_is_activated('pos_system'))
                        <div class="form-group row">
                            <label class="col-lg-3 col-from-label">{{translate('Barcode')}}</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" name="barcode" placeholder="{{ translate('Barcode') }}" value="{{ $product->barcode }}">
                            </div>
                        </div>
                        @endif

                        @if (addon_is_activated('refund_request'))
                        <div class="form-group row">
                            <label class="col-lg-3 col-from-label">{{translate('Refundable')}}</label>
                            <div class="col-lg-8">
                                <label class="aiz-switch aiz-switch-success mb-0" style="margin-top:5px;">
                                    <input type="checkbox" name="refundable" @if ($product->refundable == 1) checked @endif value="1">
                                    <span class="slider round"></span></label>
                                </label>
                            </div>
                        </div>
                        @endif -->
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Product Images')}}</h5>
                    </div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Gallery Images')}}</label>
                            <div class="col-md-8">
                                <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="photos" value="{{ $product->photos }}" class="selected-files">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Thumbnail Image')}} <small>(290x300)</small></label>
                            <div class="col-md-8">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="thumbnail_img" value="{{ $product->thumbnail_img }}" class="selected-files">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>
                        {{-- <div class="form-group row">
                                                    <label class="col-lg-3 col-from-label">{{translate('Gallery Images')}}</label>
                        <div class="col-lg-8">
                            <div id="photos">
                                @if(is_array(json_decode($product->photos)))
                                @foreach (json_decode($product->photos) as $key => $photo)
                                <div class="col-md-4 col-sm-4 col-xs-6">
                                    <div class="img-upload-preview">
                                        <img loading="lazy"  src="{{ uploaded_asset($photo) }}" alt="" class="img-responsive">
                                            <input type="hidden" name="previous_photos[]" value="{{ $photo }}">
                                            <button type="button" class="btn btn-danger close-btn remove-files"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                    </div> --}}
                        {{-- <div class="form-group row">
                            <label class="col-lg-3 col-from-label">{{translate('Thumbnail Image')}} <small>(290x300)</small></label>
                            <div class="col-lg-8">
                                <div id="thumbnail_img">
                                    @if ($product->thumbnail_img != null)
                                    <div class="col-md-4 col-sm-4 col-xs-6">
                                        <div class="img-upload-preview">
                                            <img loading="lazy"  src="{{ uploaded_asset($product->thumbnail_img) }}" alt="" class="img-responsive">
                                            <input type="hidden" name="previous_thumbnail_img" value="{{ $product->thumbnail_img }}">
                                            <button type="button" class="btn btn-danger close-btn remove-files"><i class="fa fa-times"></i></button>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
                <!-- <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Product Videos')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-lg-3 col-from-label">{{translate('Video Provider')}}</label>
                            <div class="col-lg-8">
                                <select class="form-control aiz-selectpicker" name="video_provider" id="video_provider">
                                    <option value="youtube" <?php if ($product->video_provider == 'youtube') echo "selected"; ?> >{{translate('Youtube')}}</option>
                                    <option value="dailymotion" <?php if ($product->video_provider == 'dailymotion') echo "selected"; ?> >{{translate('Dailymotion')}}</option>
                                    <option value="vimeo" <?php if ($product->video_provider == 'vimeo') echo "selected"; ?> >{{translate('Vimeo')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-from-label">{{translate('Video Link')}}</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" name="video_link" value="{{ $product->video_link }}" placeholder="{{ translate('Video Link') }}">
                            </div>
                        </div>
                    </div>
                </div> -->
                <!-- <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Product Variation')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row gutters-5">
                            <div class="col-lg-3">
                                <input type="text" class="form-control" value="{{translate('Colors')}}" disabled>
                            </div>
                            <div class="col-lg-8">
                                <select class="form-control aiz-selectpicker" data-live-search="true" data-selected-text-format="count" name="colors[]" id="colors" multiple>
                                    @foreach (\App\Models\Color::orderBy('name', 'asc')->get() as $key => $color)
                                    <option
                                        value="{{ $color->code }}"
                                        data-content="<span><span class='size-15px d-inline-block mr-2 rounded border' style='background:{{ $color->code }}'></span><span>{{ $color->name }}</span></span>"
                                        <?php if (in_array($color->code, json_decode($product->colors))) echo 'selected' ?>
                                        ></option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-1">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input value="1" type="checkbox" name="colors_active" <?php if (count(json_decode($product->colors)) > 0) echo "checked"; ?> >
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group row gutters-5">
                            <div class="col-lg-3">
                                <input type="text" class="form-control" value="{{translate('Attributes')}}" disabled>
                            </div>
                            <div class="col-lg-8">
                                <select name="choice_attributes[]" id="choice_attributes" data-selected-text-format="count" data-live-search="true" class="form-control aiz-selectpicker" multiple data-placeholder="{{ translate('Choose Attributes') }}">
                                    @foreach (\App\Models\Attribute::all() as $key => $attribute)
                                    <option value="{{ $attribute->id }}" @if($product->attributes != null && in_array($attribute->id, json_decode($product->attributes, true))) selected @endif>{{ $attribute->getTranslation('name') }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="">
                            <p>{{ translate('Choose the attributes of this product and then input values of each attribute') }}</p>
                            <br>
                        </div>

                        <div class="customer_choice_options" id="customer_choice_options">
                            @foreach (json_decode($product->choice_options) as $key => $choice_option)
                            <div class="form-group row">
                                <div class="col-lg-3">
                                    <input type="hidden" name="choice_no[]" value="{{ $choice_option->attribute_id }}">
                                    <input type="text" class="form-control" name="choice[]" value="{{ optional(\App\Models\Attribute::find($choice_option->attribute_id))->getTranslation('name') }}" placeholder="{{ translate('Choice Title') }}" disabled>
                                </div>
                                <div class="col-lg-8">
                                    <select class="form-control aiz-selectpicker attribute_choice" data-live-search="true" name="choice_options_{{ $choice_option->attribute_id }}[]" multiple>
                                        @foreach (\App\Models\AttributeValue::where('attribute_id', $choice_option->attribute_id)->get() as $row)
                                        <option value="{{ $row->value }}" @if( in_array($row->value, $choice_option->values)) selected @endif>
                                            {{ $row->value }}
                                        </option>
                                        @endforeach
                                    </select>
                                    {{-- <input type="text" class="form-control aiz-tag-input" name="choice_options_{{ $choice_option->attribute_id }}[]" placeholder="{{ translate('Enter choice values') }}" value="{{ implode(',', $choice_option->values) }}" data-on-change="update_sku"> --}}
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div> -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Product price + stock')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-lg-3 col-from-label">{{translate('Unit price')}}</label>
                            <div class="col-lg-6">
                                <input type="text" placeholder="{{translate('Unit price')}}" name="unit_price" id="unit_price" class="form-control" value="{{$product->unit_price}}" onkeyup="updatePrice('unit_price')" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-from-label">{{translate('Purchase Price')}}</label>
                            <div class="col-lg-6">
                                <input type="text" placeholder="{{translate('Purchase price')}}" name="purchase_price" id="purchase_price" class="form-control" value="{{$product->purchase_price}}" onkeyup="updatePrice('purchase_price')" >
                            </div>
                        </div>

                        <!-- @php
                          $start_date = date('d-m-Y H:i:s', $product->discount_start_date);
                          $end_date = date('d-m-Y H:i:s', $product->discount_end_date);
                        @endphp

                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="start_date">{{translate('Discount Date Range')}}</label>
                            <div class="col-sm-9"> -->
                              <input type="hidden" class="form-control" @if($product->discount_start_date && $product->discount_end_date) value="{{ $start_date.' to '.$end_date }}" @endif name="date_range" placeholder="{{translate('Select Date')}}" data-time-picker="true" data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off">
                            <!-- </div>
                        </div> -->

                        <div class="form-group row">
                            <label class="col-lg-3 col-from-label">{{translate('Discount')}}</label>
                            <div class="col-lg-6">
                                <input type="number" lang="en" min="0" step="0.01" placeholder="{{translate('Discount')}}" name="discount" id="discount" class="form-control" value="{{ $product->discount>0 ? $product->discount:0 }}" onkeyup="updatePrice('discount')">
                            </div>

                            <input type="hidden" class="form-control" name="discount_type" value="percent">
                            <!-- <div class="col-lg-3">
                                <select class="form-control aiz-selectpicker" name="discount_type" required>
                                    <option value="amount" <?php if ($product->discount_type == 'amount') echo "selected"; ?> >{{translate('Flat')}}</option>
                                    <option value="percent" <?php if ($product->discount_type == 'percent') echo "selected"; ?> >{{translate('Percent')}}</option>
                                </select>
                            </div> -->
                        </div>

                        <!-- @if(addon_is_activated('club_point'))
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">
                                    {{translate('Set Point')}}
                                </label>
                                <div class="col-md-6">
                                    <input type="number" lang="en" min="0" value="{{ $product->earn_point }}" step="1" placeholder="{{ translate('1') }}" name="earn_point" class="form-control">
                                </div>
                            </div>
                        @endif -->

                        <div id="show-hide-div">
                            <div class="form-group row" id="quantity">
                                <label class="col-lg-3 col-from-label">{{translate('Stock')}}</label>
                                <div class="col-lg-6">
                                    <input type="number" lang="en" value="{{ optional($product->stocks->first())->qty }}" step="1" placeholder="{{translate('Stock')}}" name="current_stock" class="form-control">
                                </div>
                            </div>
                            <!-- <div class="form-group row">
                                <label class="col-md-3 col-from-label">
                                    {{translate('SKU')}}
                                </label>
                                <div class="col-md-6">
                                    <input type="text" placeholder="{{ translate('SKU') }}" value="{{ optional($product->stocks->first())->sku }}" name="sku" class="form-control">
                                </div>
                            </div> -->
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">
                                {{translate('External link')}}
                            </label>
                            <div class="col-md-9">
                                <input type="text" placeholder="{{ translate('External link') }}" name="external_link" value="{{ $product->external_link }}" class="form-control">
                                <small class="text-muted">{{translate('Leave it blank if you do not use external site link')}}</small>
                            </div>
                        </div>
                        <!-- <div class="form-group row">
                            <label class="col-md-3 col-from-label">
                                {{translate('External link button text')}}
                            </label>
                            <div class="col-md-9">
                                <input type="text" placeholder="{{ translate('External link button text') }}" name="external_link_btn" value="{{ $product->external_link_btn }}" class="form-control">
                                <small class="text-muted">{{translate('Leave it blank if you do not use external site link')}}</small>
                            </div>
                        </div> -->
                        <br>
                        <!-- <div class="sku_combination" id="sku_combination">

                        </div> -->
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Short Description')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <!-- <label class="col-lg-3 col-from-label">{{translate('Short Description')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label> -->
                            <div class="col-lg-12">
                                <textarea class="aiz-text-editor" name="short_description">{{ $product->getTranslation('short_description', $lang) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Product Description')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <!-- <label class="col-lg-3 col-from-label">{{translate('Description')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label> -->
                            <div class="col-lg-12">
                                <textarea class="aiz-text-editor" name="description">{{ $product->getTranslation('description', $lang) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Product Guide')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <!-- <label class="col-lg-3 col-from-label">{{translate('Product Guide')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label> -->
                            <div class="col-lg-12">
                                <textarea class="aiz-text-editor" name="guide">{{ $product->getTranslation('guide', $lang) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

<!--                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Product Shipping Cost')}}</h5>
                    </div>
                    <div class="card-body">

                    </div>
                </div>-->

                <!-- <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('PDF Specification')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('PDF Specification')}}</label>
                            <div class="col-md-8">
                                <div class="input-group" data-toggle="aizuploader">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="pdf" value="{{ $product->pdf }}" class="selected-files">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
               <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Slug')}}</h5>
                    </div>
                    <div class="card-body">
                         <!-- <div class="form-group row">
                            <label class="col-lg-3 col-from-label">{{translate('Meta Title')}}</label>
                            <div class="col-lg-8"> -->
                                <input type="hidden" class="form-control" name="meta_title" value="{{ $product->meta_title }}" placeholder="{{translate('Meta Title')}}">
                            <!-- </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-from-label">{{translate('Description')}}</label>
                            <div class="col-lg-8"> -->
                                <textarea name="meta_description" rows="8" style="display:none" class="form-control">{{ $product->meta_description }}</textarea>
                            <!-- </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Meta Images')}}</label>
                            <div class="col-md-8">
                                <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div> -->
                                    <input type="hidden" name="meta_img" value="{{ $product->meta_img }}" class="selected-files">
                                <!-- </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div> -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{translate('Slug')}}</label>
                            <div class="col-md-8">
                                <input type="text" placeholder="{{translate('Slug')}}" id="slug" name="slug" value="{{ $product->slug }}" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">

                <!-- <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6" class="dropdown-toggle" data-toggle="collapse" data-target="#collapse_2">
                            {{translate('Shipping Configuration')}}
                        </h5>
                    </div>
                    <div class="card-body collapse show" id="collapse_2">
                        @if (get_setting('shipping_type') == 'product_wise_shipping')
                        <div class="form-group row">
                            <label class="col-lg-6 col-from-label">{{translate('Free Shipping')}}</label>
                            <div class="col-lg-6">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="radio" name="shipping_type" value="free" @if($product->shipping_type == 'free') checked @endif>
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-6 col-from-label">{{translate('Flat Rate')}}</label>
                            <div class="col-lg-6">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="radio" name="shipping_type" value="flat_rate" @if($product->shipping_type == 'flat_rate') checked @endif>
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="flat_rate_shipping_div" style="display: none">
                            <div class="form-group row">
                                <label class="col-lg-6 col-from-label">{{translate('Shipping cost')}}</label>
                                <div class="col-lg-6">
                                    <input type="number" lang="en" min="0" value="{{ $product->shipping_cost }}" step="0.01" placeholder="{{ translate('Shipping cost') }}" name="flat_shipping_cost" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-6 col-from-label">{{translate('Is Product Quantity Mulitiply')}}</label>
                            <div class="col-md-6">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="checkbox" name="is_quantity_multiplied" value="1" @if($product->is_quantity_multiplied == 1) checked @endif>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        @else
                        <p>
                            {{ translate('Product wise shipping cost is disable. Shipping cost is configured from here') }}
                            <a href="{{route('shipping_configuration.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['shipping_configuration.index','shipping_configuration.edit','shipping_configuration.update'])}}">
                                <span class="aiz-side-nav-text">{{translate('Shipping Configuration')}}</span>
                            </a>
                        </p>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Low Stock Quantity Warning')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="name">
                                {{translate('Quantity')}}
                            </label>
                            <input type="number" name="low_stock_quantity" value="{{ $product->low_stock_quantity }}" min="0" step="1" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">
                            {{translate('Stock Visibility State')}}
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-md-6 col-from-label">{{translate('Show Stock Quantity')}}</label>
                            <div class="col-md-6">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="radio" name="stock_visibility_state" value="quantity" @if($product->stock_visibility_state == 'quantity') checked @endif>
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-6 col-from-label">{{translate('Show Stock With Text Only')}}</label>
                            <div class="col-md-6">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="radio" name="stock_visibility_state" value="text" @if($product->stock_visibility_state == 'text') checked @endif>
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-6 col-from-label">{{translate('Hide Stock')}}</label>
                            <div class="col-md-6">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="radio" name="stock_visibility_state" value="hide" @if($product->stock_visibility_state == 'hide') checked @endif>
                                    <span></span>
                                </label>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Cash On Delivery')}}</h5>
                    </div>
                    <div class="card-body">
                        @if (get_setting('cash_payment') == '1')
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-md-6 col-from-label">{{translate('Status')}}</label>
                                    <div class="col-md-6">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="checkbox" name="cash_on_delivery" value="1" @if($product->cash_on_delivery == 1) checked @endif>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                            <p>
                                {{ translate('Cash On Delivery option is disabled. Activate this feature from here') }}
                                <a href="{{route('activation.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['shipping_configuration.index','shipping_configuration.edit','shipping_configuration.update'])}}">
                                    <span class="aiz-side-nav-text">{{translate('Cash Payment Activation')}}</span>
                                </a>
                            </p>
                        @endif
                    </div>
                </div> -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Is Hot')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-md-6 col-from-label">{{translate('Status')}}</label>
                                    <div class="col-md-6">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="checkbox" name="featured" value="1" @if($product->featured == 1) checked @endif>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Is New')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-md-6 col-from-label">{{translate('Status')}}</label>
                                    <div class="col-md-6">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="checkbox" name="todays_deal" value="1" @if($product->todays_deal == 1) checked @endif>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('See Product To Website')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="btn-group mt-2 mr-2">
                                    <button type="submit" name="button" class="btn btn-success">{{ translate('Update Product') }}</button>
                                </div>
                                <div class="btn-group mt-2 mr-2">
                                    <button type="button" target="_blank" class="btn btn-info" data-toggle="modal" data-target="#myModal">{{ translate('Preview') }}</button>  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Flash Deal')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="name">
                                {{translate('Add To Flash')}}
                            </label>
                            <select class="form-control aiz-selectpicker" name="flash_deal_id" id="video_provider">
                                <option value="">Choose Flash Title</option>
                                @foreach(\App\Models\FlashDeal::where("status", 1)->get() as $flash_deal)
                                    <option value="{{ $flash_deal->id }}" @if($product->flash_deal_product && $product->flash_deal_product->flash_deal_id == $flash_deal->id) selected @endif>
                                        {{ $flash_deal->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="name">
                                {{translate('Discount')}}
                            </label>
                            <input type="number" name="flash_discount" value="{{ $product->discount }}" min="0" step="1" class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label for="name">
                                {{translate('Discount Type')}}
                            </label>
                            <select class="form-control aiz-selectpicker" name="flash_discount_type" id="">
                                <option value="">Choose Discount Type</option>
                                <option value="amount" @if($product->discount_type == 'amount') selected @endif>
                                    {{translate('Flat')}}
                                </option>
                                <option value="percent" @if($product->discount_type == 'percent') selected @endif>
                                    {{translate('Percent')}}
                                </option>
                            </select>
                        </div>
                    </div>
                </div> -->

                <!-- <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Estimate Shipping Time')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="name">
                                {{translate('Shipping Days')}}
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="est_shipping_days" value="{{ $product->est_shipping_days }}" min="1" step="1" placeholder="{{translate('Shipping Days')}}">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroupPrepend">{{translate('Days')}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->

                <!-- <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('VAT & Tax')}}</h5>
                    </div>
                    <div class="card-body">
                        @foreach(\App\Models\Tax::where('tax_status', 1)->get() as $tax)
                        <label for="name">
                            {{$tax->name}}
                            <input type="hidden" value="{{$tax->id}}" name="tax_id[]">
                        </label>

                        @php
                        $tax_amount = 0;
                        $tax_type = '';
                        foreach($tax->product_taxes as $row) {
                            if($product->id == $row->product_id) {
                                $tax_amount = $row->tax;
                                $tax_type = $row->tax_type;
                            }
                        }
                        @endphp

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <input type="number" lang="en" min="0" value="{{ $tax_amount }}" step="0.01" placeholder="{{ translate('Tax') }}" name="tax[]" class="form-control" required>
                            </div>
                            <div class="form-group col-md-6">
                                <select class="form-control aiz-selectpicker" name="tax_type[]">
                                    <option value="amount" @if($tax_type == 'amount') selected @endif>
                                        {{translate('Flat')}}
                                    </option>
                                    <option value="percent" @if($tax_type == 'percent') selected @endif>
                                        {{translate('Percent')}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div> -->

            </div>
            <!-- <div class="col-12">
                <div class="mb-3 text-right">
                    <button type="submit" name="button" class="btn btn-info">{{ translate('Update Product') }}</button>
                </div>
            </div> -->
        </div>
    </form>
</div>

<!-- Include Modal File -->
@include('modals.preview_modals')

@endsection

@section('script')

<script type="text/javascript">

function updatePrice(type){

var unit_price=parseFloat($('#unit_price').val());
var discount=parseFloat($('#discount').val());
var purchase_price=parseFloat($('#purchase_price').val());

if(type=='discount'){

    if(discount>=0 && unit_price>0){

        var discount_amount=(unit_price/100)*discount;
        var purchase_price=unit_price-discount_amount;

        $('#purchase_price').val(Math.round(purchase_price,2));

    }
}else{

    if(purchase_price>0 && unit_price>=purchase_price){

        var diff=unit_price-purchase_price;
        var discount=diff/unit_price*100;
        $('#discount').val(Math.round(discount,2));

    }

    
}




}
    $(document).ready(function (){
        show_hide_shipping_div();
    });

    $("[name=shipping_type]").on("change", function (){
        show_hide_shipping_div();
    });

    function show_hide_shipping_div() {
        var shipping_val = $("[name=shipping_type]:checked").val();

        $(".flat_rate_shipping_div").hide();

        if(shipping_val == 'flat_rate'){
            $(".flat_rate_shipping_div").show();
        }
    }

    function add_more_customer_choice_option(i, name){
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:"POST",
            url:'{{ route('products.add-more-choice-option') }}',
            data:{
               attribute_id: i
            },
            success: function(data) {
                var obj = JSON.parse(data);
                $('#customer_choice_options').append('\
                <div class="form-group row">\
                    <div class="col-md-3">\
                        <input type="hidden" name="choice_no[]" value="'+i+'">\
                        <input type="text" class="form-control" name="choice[]" value="'+name+'" placeholder="{{ translate('Choice Title') }}" readonly>\
                    </div>\
                    <div class="col-md-8">\
                        <select class="form-control aiz-selectpicker attribute_choice" data-live-search="true" name="choice_options_'+ i +'[]" multiple>\
                            '+obj+'\
                        </select>\
                    </div>\
                </div>');
                AIZ.plugins.bootstrapSelect('refresh');
           }
       });


    }

    $('input[name="colors_active"]').on('change', function() {
        if(!$('input[name="colors_active"]').is(':checked')){
            $('#colors').prop('disabled', true);
            AIZ.plugins.bootstrapSelect('refresh');
        }
        else{
            $('#colors').prop('disabled', false);
            AIZ.plugins.bootstrapSelect('refresh');
        }
        update_sku();
    });

    $(document).on("change", ".attribute_choice",function() {
        update_sku();
    });

    $('#colors').on('change', function() {
        update_sku();
    });

    function delete_row(em){
        $(em).closest('.form-group').remove();
        update_sku();
    }

    function delete_variant(em){
        $(em).closest('.variant').remove();
    }

    function update_sku(){
        $.ajax({
           type:"POST",
           url:'{{ route('products.sku_combination_edit') }}',
           data:$('#choice_form').serialize(),
           success: function(data){
                $('#sku_combination').html(data);
                AIZ.uploader.previewGenerate();
                AIZ.plugins.fooTable();
                if (data.length > 1) {
                    $('#show-hide-div').hide();
                }
                else {
                    $('#show-hide-div').show();
                }
           }
        });
    }

    AIZ.plugins.tagify();

    $(document).ready(function(){
        update_sku();

        $('.remove-files').on('click', function(){
            $(this).parents(".col-md-4").remove();
        });
    });

    $('#choice_attributes').on('change', function() {
        $.each($("#choice_attributes option:selected"), function(j, attribute){
            flag = false;
            $('input[name="choice_no[]"]').each(function(i, choice_no) {
                if($(attribute).val() == $(choice_no).val()){
                    flag = true;
                }
            });
            if(!flag){
                add_more_customer_choice_option($(attribute).val(), $(attribute).text());
            }
        });

        var str = @php echo $product->attributes @endphp;

        $.each(str, function(index, value){
            flag = false;
            $.each($("#choice_attributes option:selected"), function(j, attribute){
                if(value == $(attribute).val()){
                    flag = true;
                }
            });
            if(!flag){
                $('input[name="choice_no[]"][value="'+value+'"]').parent().parent().remove();
            }
        });

        update_sku();
    });


//Modal
$(document).ready(function(){
    
    @if(request()->get('preview'))
		$("#myModal").modal('show');
    @endif

//onclick active responsive button
var $links = $('.modal__button-item .btn');
$links.click(function(){
   $links.removeClass('active');
   $(this).addClass('active');
});

        //responsive view mobile button js
        $('.btn__mobile').click(function() {
    $('.modal-content .modal-body').css({
        'width': '375px',
        'margin-inline' : 'auto'
    });
})
        //responsive view tablet button js
        $('.btn__tablet').click(function() {
    $('.modal-content .modal-body').css({
        'width': '768px',
        'margin-inline' : 'auto'
    });
})

        //responsive view desktop button js
        $('.btn__desktop').click(function() {
    $('.modal-content .modal-body').css({
        'width': '1350px',
        'margin-inline' : 'auto'
    });
})

});



</script>

@endsection
