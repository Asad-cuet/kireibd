<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\ProductCollection;
use App\Http\Resources\V2\ProductMiniCollection;
use App\Http\Resources\V2\ProductNuxtMiniCollection;
use App\Http\Resources\V2\ProductDetailCollection;
use App\Http\Resources\V2\FlashDealCollection;
use App\Models\FlashDeal;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Color;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Utility\CategoryUtility;
use App\Utility\SearchUtility;
use Cache;
use DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        return new ProductMiniCollection(Product::latest()->paginate(10));
    }


    public function getSubCategories($category)
    {

        $category_ids=[];

        foreach ($category->categories as $item) {
            $category_ids[]=$item->id;
            if(count($item->categories)>0){                
                $subcategories_ids=$this->getSubCategories($item);
                $category_ids=array_merge($category_ids,$subcategories_ids);
            }
        }

        return $category_ids;

    }
    public function filterProduct(Request $request)
    {
        if($request->type=='new-arrivals'){
            $data=Product::leftJoin('brands','products.brand_id','brands.id')->where('published',1)->where('todays_deal','1')->select('products.*','brands.slug as brand_slug');
        }else{
            $data=Product::leftJoin('brands','products.brand_id','brands.id')->where('published',1)->select('products.*','brands.slug as brand_slug');
        }


        $per_page=trim($request->per_page);
        $per_page= $per_page > 0 ? $per_page:24;


        $category_slug=trim($request->category);

        if($category_slug!=''){

            $category=Category::where('slug', $category_slug)->first();
            $category_ids=[];
            
            if($category && $category->id){           
                $category_ids=$this->getSubCategories($category);
                $category_ids[]=$category->id;
            }

            $data=$data->whereIn('products.category_id', $category_ids);
        }

        $brand_slug=trim($request->brand);

        if($brand_slug!=''){
            $data=$data->where('brands.slug',$brand_slug);
        }

        if($request->min_price>0){
            $data=$data->where('purchase_price','>=',$request->min_price);
        }
        if($request->max_price>0){
            $data=$data->where('purchase_price','<=',$request->max_price);
        }

        $tag=trim($request->tag);

        if($tag!=''){
            $data=$data->where('tags','LIKE','%'.$tag.'%');
        }

        
        $skin_type=trim($request->type);
        
        if($skin_type!=''){
            
            $skin_types=explode(',',$request->type);
            
            $data=$data->where(function ($query) use ($skin_types) {

                foreach ($skin_types as $type) {
                    $query=$query->orWhere('skin_types','LIKE','%'.$type.'%');
                }

            });
        }

        $key=trim($request->search);

        if($key!=''){

            $data=$data->where(function ($query) use ($key) {

                $search_columns=['products.name'];

                foreach($search_columns as $column){
                    $query=$query->orWhere($column,'LIKE','%'.$key.'%');
                }
            });

            $with_data[]=array('search'=>$key);
        }

        $order_by=trim($request->order_by);
        
        switch ($order_by) {
            case 'bestseller':
                $data=$data->orderBy('num_of_sale', 'desc');
                break;
            case 'rating':
                $data=$data->orderBy('rating', 'desc');
                break;
            case 'new':
                $data=$data->orderBy('todays_deal');
                break;
            case 'hot':
                $data=$data->orderBy('featured');
                break;
            
            case 'price-asc':
                $data=$data->orderBy('purchase_price', 'asc');
                break;
            
            case 'price-desc':
                $data=$data->orderBy('purchase_price', 'desc');
                break;
            
            default:
                $data=$data->orderBy('created_at', 'desc');
                break;
        }

        $data=$data->paginate($per_page);
        
        return new ProductNuxtMiniCollection($data);
    }


    public function searchProduct(Request $request)
    {
        $category_ids = [];
        $brand_ids = [];

        if ($request->categories != null && $request->categories != "") {
            $category_ids = explode(',', $request->categories);
        }

        if ($request->brands != null && $request->brands != "") {
            $brand_ids = explode(',', $request->brands);
        }

        $sort_by = $request->sort_key;
        $name = $request->name;
        $min = $request->min;
        $max = $request->max;


        $products = Product::query();

        $products->where('published', 1)->physical();

        if (!empty($brand_ids)) {
            $products->whereIn('brand_id', $brand_ids);
        }

        if (!empty($category_ids)) {
            $n_cid = [];
            foreach ($category_ids as $cid) {
                $n_cid = array_merge($n_cid, CategoryUtility::children_ids($cid));
            }

            if (!empty($n_cid)) {
                $category_ids = array_merge($category_ids, $n_cid);
            }

            $products->whereIn('category_id', $category_ids);
        }

        if ($name != null && $name != "") {
            $products->where(function ($query) use ($name) {
                foreach (explode(' ', trim($name)) as $word) {
                    $query->where('name', 'like', '%'.$word.'%')->orWhere('tags', 'like', '%'.$word.'%')->orWhereHas('product_translations', function($query) use ($word){
                        $query->where('name', 'like', '%'.$word.'%');
                    });
                }
            });
            SearchUtility::store($name);
        }

        if ($min != null && $min != "" && is_numeric($min)) {
            $products->where('unit_price', '>=', $min);
        }

        if ($max != null && $max != "" && is_numeric($max)) {
            $products->where('unit_price', '<=', $max);
        }

        switch ($sort_by) {
            case 'price_low_to_high':
                $products->orderBy('unit_price', 'asc');
                break;

            case 'price_high_to_low':
                $products->orderBy('unit_price', 'desc');
                break;

            case 'new_arrival':
                $products->orderBy('created_at', 'desc');
                break;

            case 'popularity':
                $products->orderBy('num_of_sale', 'desc');
                break;

            case 'top_rated':
                $products->orderBy('rating', 'desc');
                break;

            default:
                $products->orderBy('created_at', 'desc');
                break;
        }

        return new ProductNuxtMiniCollection(filter_products($products)->paginate(10));
    }



       

    public function show(Request $request, $slug)
    {

        if($request->nuxt){

            $data= new \stdClass();

            $product=Product::where('slug', $slug)->first();

            if($product!=''){
                $data->product=$this->formatProduct($product);
                
                if(!$request->quick_view){

                    $relatedProducts = [];
                    $related=Product::where('id','>=',10)->latest()->get();

                    foreach($related as $item){
                        $relatedProducts[]=$this->formatProduct($item);  
                    }

                    $next_product=Product::where('id',  $product->id+1)->first();

                    if($next_product!=''){
                        $nextProduct=$this->formatProduct($next_product);   
                    }else{
                        $nextProduct=null;
                    }

                    $prev_product=Product::where('id',  $product->id-1)->first();

                    if($prev_product!=''){
                        $prevProduct=$this->formatProduct($prev_product);   
                    }else{
                        $prevProduct=null;
                    }

                    $data->nextProduct=$nextProduct;
                    $data->prevProduct=$prevProduct;
                    $data->relatedProducts=$relatedProducts;
                    $data->featuredProducts=$relatedProducts;
                    $data->bestSellingProducts=$relatedProducts;
                    $data->latestProducts=$relatedProducts;
                    $data->topRatedProducts=$relatedProducts;
                }


            }else{
                return response()->json([
                    'result' => false,
                    'message' => translate('Product not found')
                ]);
            }


            
            return $data;            
        }
        return new ProductDetailCollection(Product::where('id', $slug)->get());
    }

    public function admin()
    {
        return new ProductCollection(Product::where('added_by', 'admin')->latest()->paginate(10));
    }

    public function seller($id, Request $request)
    {
        $shop = Shop::findOrFail($id);
        $products = Product::where('added_by', 'seller')->where('user_id', $shop->user_id);
        if ($request->name != "" || $request->name != null) {
            $products = $products->where('name', 'like', '%' . $request->name . '%');
        }
        $products->where('published', 1);
        return new ProductMiniCollection($products->latest()->paginate(10));
    }

    public function category($id, Request $request)
    {
        $category_ids = CategoryUtility::children_ids($id);
        $category_ids[] = $id;

        $products = Product::whereIn('category_id', $category_ids)->physical();

        if ($request->name != "" || $request->name != null) {
            $products = $products->where('name', 'like', '%' . $request->name . '%');
        }
        $products->where('published', 1);
        return new ProductMiniCollection(filter_products($products)->latest()->paginate(10));
    }


    public function brand($id, Request $request)
    {
        $products = Product::where('brand_id', $id)->physical();
        if ($request->name != "" || $request->name != null) {
            $products = $products->where('name', 'like', '%' . $request->name . '%');
        }

        return new ProductMiniCollection(filter_products($products)->latest()->paginate(10));
    }

    public function todaysDeal()
    {
        return Cache::remember('app.todays_deal', 86400, function(){
            $products = Product::where('todays_deal', 1)->physical();
            return new ProductMiniCollection(filter_products($products)->limit(20)->latest()->get());
        });
    }

    public function flashDeal()
    {
        return Cache::remember('app.flash_deals', 86400, function(){
            $flash_deals = FlashDeal::where('status', 1)->where('featured', 1)->where('start_date', '<=', strtotime(date('d-m-Y')))->where('end_date', '>=', strtotime(date('d-m-Y')))->get();
            return new FlashDealCollection($flash_deals);
        });
    }

    public function featured()
    {
        $products = Product::where('featured', 1)->physical();
        return new ProductMiniCollection(filter_products($products)->latest()->paginate(10));
    }

    public function bestSeller()
    {
        return Cache::remember('app.best_selling_products', 86400, function(){
            $products = Product::orderBy('num_of_sale', 'desc')->physical();
            return new ProductMiniCollection(filter_products($products)->limit(20)->get());
        });
    }

    public function related($id)
    {
        return Cache::remember("app.related_products-$id", 86400, function() use ($id){
            $product = Product::find($id);
            $products = Product::where('category_id', $product->category_id)->where('id', '!=', $id)->physical();
            return new ProductMiniCollection(filter_products($products)->limit(10)->get());
        });
    }

    public function topFromSeller($id)
    {
        return Cache::remember("app.top_from_this_seller_products-$id", 86400, function() use ($id){
            $product = Product::find($id);
            $products = Product::where('user_id', $product->user_id)->orderBy('num_of_sale', 'desc')->physical();

            return new ProductMiniCollection(filter_products($products)->limit(10)->get());
        });
    }


    public function search(Request $request)
    {
        $category_ids = [];
        $brand_ids = [];

        if ($request->categories != null && $request->categories != "") {
            $category_ids = explode(',', $request->categories);
        }

        if ($request->brands != null && $request->brands != "") {
            $brand_ids = explode(',', $request->brands);
        }

        $sort_by = $request->sort_key;
        $name = $request->name;
        $min = $request->min;
        $max = $request->max;


        $products = Product::query();

        $products->where('published', 1)->physical();

        if (!empty($brand_ids)) {
            $products->whereIn('brand_id', $brand_ids);
        }

        if (!empty($category_ids)) {
            $n_cid = [];
            foreach ($category_ids as $cid) {
                $n_cid = array_merge($n_cid, CategoryUtility::children_ids($cid));
            }

            if (!empty($n_cid)) {
                $category_ids = array_merge($category_ids, $n_cid);
            }

            $products->whereIn('category_id', $category_ids);
        }

        if ($name != null && $name != "") {
            $products->where(function ($query) use ($name) {
                foreach (explode(' ', trim($name)) as $word) {
                    $query->where('name', 'like', '%'.$word.'%')->orWhere('tags', 'like', '%'.$word.'%')->orWhereHas('product_translations', function($query) use ($word){
                        $query->where('name', 'like', '%'.$word.'%');
                    });
                }
            });
            SearchUtility::store($name);
        }

        if ($min != null && $min != "" && is_numeric($min)) {
            $products->where('unit_price', '>=', $min);
        }

        if ($max != null && $max != "" && is_numeric($max)) {
            $products->where('unit_price', '<=', $max);
        }

        switch ($sort_by) {
            case 'price_low_to_high':
                $products->orderBy('unit_price', 'asc');
                break;

            case 'price_high_to_low':
                $products->orderBy('unit_price', 'desc');
                break;

            case 'new_arrival':
                $products->orderBy('created_at', 'desc');
                break;

            case 'popularity':
                $products->orderBy('num_of_sale', 'desc');
                break;

            case 'top_rated':
                $products->orderBy('rating', 'desc');
                break;

            default:
                $products->orderBy('created_at', 'desc');
                break;
        }

        return new ProductMiniCollection(filter_products($products)->paginate(10));
    }

    public function variantPrice(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $str = '';
        $tax = 0;

        if ($request->has('color') && $request->color != "") {
            $str = Color::where('code', '#' . $request->color)->first()->name;
        }

        $var_str = str_replace(',', '-', $request->variants);
        $var_str = str_replace(' ', '', $var_str);

        if ($var_str != "") {
            $temp_str = $str == "" ? $var_str : '-' . $var_str;
            $str .= $temp_str;
        }


        $product_stock = $product->stocks->where('variant', $str)->first();
        $price = $product_stock->price;
        $stockQuantity = $product_stock->qty;


        //discount calculation
        $discount_applicable = false;

        if ($product->discount_start_date == null) {
            $discount_applicable = true;
        } elseif (strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
            strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date) {
            $discount_applicable = true;
        }

        if ($discount_applicable) {
            if ($product->discount_type == 'percent') {
                $price -= ($price * $product->discount) / 100;
            } elseif ($product->discount_type == 'amount') {
                $price -= $product->discount;
            }
        }

        if ($product->tax_type == 'percent') {
            $price += ($price * $product->tax) / 100;
        } elseif ($product->tax_type == 'amount') {
            $price += $product->tax;
        }



        return response()->json([
            'product_id' => $product->id,
            'variant' => $str,
            'price' => (double)convert_price($price),
            'price_string' => format_price(convert_price($price)),
            'stock' => intval($stockQuantity),
            'image' => $product_stock->image == null ? "" : uploaded_asset($product_stock->image) 
        ]);
    }

    public function home()
    {
        return new ProductCollection(Product::inRandomOrder()->physical()->take(50)->get());
    }

    public function homeProducts()
    {
        
        return Cache::remember('app.home_products', 86400, function(){
        
            $bestselling_products=new ProductNuxtMiniCollection(filter_products(Product::where('published', 1)->orderBy('num_of_sale', 'desc')->physical())->limit(20)->get());
            $new_products=new ProductNuxtMiniCollection(filter_products(Product::where('published', 1)->where('todays_deal', '1'))->limit(20)->get());
            $featured_products=new ProductNuxtMiniCollection(filter_products(Product::where('published', 1)->where('featured', '1'))->limit(20)->get());

            $data['new_products']=$new_products;
            $data['bestselling_products']=$bestselling_products;
            $data['featured_products']=$featured_products;
            $data['success']=true;
            $data['status']=200;
            
            
            return response()->json($data);

        });
    }

    public function sidebarData(Request $request)
    {

        $data= new \stdClass();

        $categoryList=[];

        $category_group = Category::get()->groupBy('parent_id');

        foreach($category_group[0] as $category){

            $category_ids=[];
            if($category && $category->id){           
                $category_ids=$this->getSubCategories($category);
                $category_ids[]=$category->id;
            }

            $item=new \stdClass();
            $item->id = $category->id;
            $item->name= $category->name;
            $item->slug= $category->slug;
            $item->counts=Product::where('published',1)->whereIn('category_id', $category_ids)->count();
            $item->disabled=true;
            $sub_categories=[];

            if(isset($category_group[$category->id])){

                foreach( $category_group[$category->id] as $sub_category){

                    $category_ids=[];
                    if($sub_category && $sub_category->id){           
                        $category_ids=$this->getSubCategories($sub_category);
                        $category_ids[]=$sub_category->id;
                    }
                    
                    $row=new \stdClass();
                    $row->id = $sub_category->id;
                    $row->name= $sub_category->name;
                    $row->slug= $sub_category->slug;
                    $row->disabled=true;
                    $row->counts=Product::where('published',1)->whereIn('category_id', $category_ids)->count();
                    $sub_categories[]=$row;

                }
            }
            $item->children=$sub_categories;

            $categoryList[]=$item;
        }


        $featuredProducts = [];
        $featured=Product::where('id','>=',5)->where('id','<',10)->latest()->get();

        foreach($featured as $item){
            $featuredProducts[]=$this->formatProduct($item);  
        }

        $data->sidebarList=$categoryList;
        $data->featuredProducts=$featuredProducts;

        return $data;
    }

    public function formatProduct($product)
    {

        $item=new \stdClass();
        $item->id = $product->id;
        $item->name= $product->name;
        $item->slug= $product->slug;
        $item->price= $product->unit_price;
        $item->sku= 'KBD-'.$product->id;
        $item->stock= $product->stocks->first()!='' ? (int)$product->stocks->first()->qty:0;
        $item->short_description= $product->getTranslation('short_description');
        $item->description= $product->getTranslation('description');
        $item->guide= $product->getTranslation('guide');
        $item->skin_types=$product->skin_types;
        $item->key_ingredients=$product->key_ingredients;
        $item->good_for=$product->good_for;
        $item->sale_price = $product->discount>0 ? getPurchasePrice($product->unit_price, $product->discount, $product->discount_type) : $product->unit_price;
        $item->sale_count= 5;
        $item->ratings= (float)$product->rating;
        $item->reviews= "0";
        $item->is_hot= null;
        $item->is_sale= $product->unit_price > $product->purchase_price ? true : false;
        $item->is_new= true;
        $item->is_out_of_stock= null;
        $item->release_date= null;
        $item->developer= null;
        $item->publisher= null;
        $item->game_mode= null;
        $item->rated= null;
        $item->until= null;
        $item->product_categories= formatCategory($product->category, $product->id);
        $item->product_brands=formatBrand($product->brand, $product->id);
        $item->product_tags=formatTags($product->tags, $product->id);
        $item->pictures=formatLargePictures($product->photos, $product->thumbnail_img, $product->id);
        $item->large_pictures=formatLargePictures($product->photos, $product->thumbnail_img, $product->id);
        $item->small_pictures=formatSmallPictures($product->photos, $product->thumbnail_img, $product->id);
        $item->variants=$this->formatVarients($item->id);

        return $item;
    }

    static function formatVarients($product_id)
    {

        // if($product_id%2==0){
            return [];
        // }

        $variants=[];

        $item= new \stdClass();
        $item1= new \stdClass();
        $pivot= new \stdClass();
        $pivot1= new \stdClass();
        $size_pivot= new \stdClass();
        $size_pivot1= new \stdClass();
        $color_pivot= new \stdClass();
        $size= new \stdClass();
        $size1= new \stdClass();
        $color= new \stdClass();
        $color_thumbnail= new \stdClass();
        $color_thumbnail_pivot= new \stdClass();

        
        $colors=[];
        $color_thumbnails=[];

        $item->id=125;

        $pivot->product_id=$product_id;
        $pivot->component_id=$item->id;

        $item->price=400;
        $item->sale_price=500;
        $item->pivot=$pivot;
        
            $size->id=120;
            $size->size_name="Small";
            $size->size="S";
        
            $size_pivot->components_variants_variant_id=$item->id;
            $size_pivot->component_id=$size->id;
        
            
            $size_thumbnails=[

            ];
            
            $size->pivot=$size_pivot;
            $size->size_thumbnail=$size_thumbnails;
            




            
            $color->id=140;
            $color->color_name="black";
            $color->color="#000000";
                $color_pivot->components_variants_variant_id=$item->id;
                $color_pivot->component_id=$color->id;
            $color->pivot=$color_pivot;
                $color_thumbnail->id=214;
                $color_thumbnail->name="Red Color";
                $color_thumbnail->alternativeText="";
                $color_thumbnail->caption="";
                $color_thumbnail->width="150";
                $color_thumbnail->height="150";
                $color_thumbnail->formats=null;
                $color_thumbnail->hash="red-color";
                $color_thumbnail->ext=".jpg";
                $color_thumbnail->mime="image/jpeg";
                $color_thumbnail->size="2.89";
                $color_thumbnail->url="Products/red.jpg";
                $color_thumbnail->previewUrl=null;
                $color_thumbnail->provider="local";
                $color_thumbnail->provider_metadata=null;
                $color_thumbnail->created_by="1";
                $color_thumbnail->updated_by="1";
                $color_thumbnail->created_at="2006-06-12T16:19:06.000000Z";
                $color_thumbnail->updated_at="2006-06-12T16:20:22.000000Z";
                
                $color_thumbnail_pivot->related_id=$color->id;
                $color_thumbnail_pivot->upload_file_id=$color_thumbnail->id;
                
                $color_thumbnail->pivot=$color_thumbnail_pivot;
                $color_thumbnails[]=$color_thumbnail;
            $color->color_thumbnail=$color_thumbnails;
            $colors[]=$color;


        $sizes=[];
        $sizes[]=$size;
        
        $item->size=$sizes;
        $item->colors=$colors;
        $item->colors=[];
        
        $variants[]=$item;

        $item1->id=126;

        $pivot1->product_id=$product_id;
        $pivot1->component_id=$item1->id;

        $item1->price=700;
        $item1->sale_price=600;
        $item1->pivot=$pivot1;


            $size1->id=121;
            $size1->size_name="Large";
            $size1->size="L";
        
            $size_pivot1->components_variants_variant_id=$item1->id;
            $size_pivot1->component_id=$size1->id;
        
            
            $size_thumbnails1=[

            ];
            
            $size1->pivot=$size_pivot;
            $size1->size_thumbnail=$size_thumbnails1;


        $sizes1=[];
        $sizes1[]=$size1;

        $item1->size=$sizes1;
        $item1->colors=[];
        
        $variants[]=$item1;

        return $variants;
    }

}
