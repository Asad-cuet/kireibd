<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BeautyBlogCategoryCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($data) {

                $points = number_format($data->points, 2, '.', '');
                $points = floatval($points);

                return [
                    'id'        => (int) $data->id,
                    'name'    => $data->name,
                    'page'    => 1,
                    'posts'    => $data->beautyBlog,
                    'date'      => date('d-m-Y', strtotime($data->created_at)),
                ];
            })
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }
}
