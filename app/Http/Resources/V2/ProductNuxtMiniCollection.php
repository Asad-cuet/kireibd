<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductNuxtMiniCollection extends ResourceCollection
{
    public function toArray($request)
    {

        return $this->collection->map(function ($data) {

                

                return [

                    'id' => $data->id,
                    'name' => $data->name,
                    'slug' => $data->slug,
                    'price' => $data->unit_price,
                    'sku' => 'KBD-'.$data->id,
                    "stock" => $data->stocks->first()!='' ? (int)$data->stocks->first()->qty:0,
                    "short_description" => $data->getTranslation('short_description'),
                    "description" => $data->getTranslation('description'),
                    "guide" => $data->getTranslation('guide'),
                    'sale_price' => $data->discount>0 ? getPurchasePrice($data->unit_price, $data->discount, $data->discount_type) : $data->unit_price,
                    'skin_types' => $data->skin_types,
                    'key_ingredients' => $data->key_ingredients,
                    'good_for' => $data->good_for,
                    "sale_count" => 5,
                    "ratings" => (float)$data->rating,
                    "reviews" => "0",
                    "is_hot" => $data->featured,
                    "is_sale" => $data->discount > 0 ? true : false,
                    "is_new" => $data->todays_deal,
                    "is_out_of_stock" => null,
                    "release_date" => null,
                    "developer" => null,
                    "publisher" => null,
                    "game_mode" => null,
                    "rated" => null,
                    "until" => null,
                    "product_categories"=> formatCategory($data->category, $data->id),
                    "product_brands"=>formatBrand($data->brand, $data->id),
                    "product_tags"=>formatTags($data->tags, $data->id),
                    "pictures"=>formatLargePictures($data->thumbnail_img, $data->photos, $data->id),
                    "large_pictures"=>formatLargePictures($data->thumbnail_img, $data->photos, $data->id),
                    "small_pictures"=>formatSmallPictures($data->thumbnail_img, $data->photos, $data->id),
                    "variants"=>$this->formatVarients($data->id),
                    

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

    public function formatVarients($product_id)
    {
        // if($product_id%2==0){
            return [];
        // }

        $variants=[];

        $item= new \stdClass();
        $pivot= new \stdClass();
        $size_pivot= new \stdClass();
        $color_pivot= new \stdClass();
        $size= new \stdClass();
        $color= new \stdClass();
        $color_thumbnail= new \stdClass();
        $color_thumbnail_pivot= new \stdClass();

        
        $sizes=[];
        $colors=[];
        $color_thumbnails=[];

        $item->id=125;

        $pivot->product_id=$product_id;
        $pivot->component_id=$item->id;

        $item->price=400;
        $item->sale_price=500;
        $item->pivot=$pivot;
        
            $size->id=120;
            $size->size_name="Extra Large";
            $size->size="XL";
        
            $size_pivot->components_variants_variant_id=$item->id;
            $size_pivot->component_id=$size->id;
        
            
            $size_thumbnails=[];
            
            $size->pivot=$size_pivot;
            $size->size_thumbnail=$size_thumbnails;
            
            $sizes[]=$size;
            
            $color->id=140;
            $color->color_name="black";
            $color->color="#000000";
                $color_pivot->components_variants_variant_id=$item->id;
                $color_pivot->component_id=$color->id;
            $color->pivot=$color_pivot;
                $color_thumbnail->id=214;
                $color_thumbnail->name="product-2-1-150x150.jpg";
                $color_thumbnail->alternativeText="";
                $color_thumbnail->caption="";
                $color_thumbnail->width="150";
                $color_thumbnail->height="150";
                $color_thumbnail->formats=null;
                $color_thumbnail->hash="product_2_1_150x150_99d2a759da";
                $color_thumbnail->ext=".jpg";
                $color_thumbnail->mime="image/jpeg";
                $color_thumbnail->size="2.89";
                $color_thumbnail->url="/uploads/product_2_1_150x150_99d2a759da.jpg";
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
        
        $item->size=$sizes;
        $item->colors=$colors;
        
        $variants[]=$item;
        return $variants;
    }
}
