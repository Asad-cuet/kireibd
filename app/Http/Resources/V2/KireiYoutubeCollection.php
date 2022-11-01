<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class KireiYoutubeCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($data) {
                return [
                    'id' => $data->id,
                    'title' => $data->title,
                    'slug' => $data->slug,
                    'category_name' => $data->kireiYoutebeCategory->name,
                    'banner' => $data->banner,
                    'short_description' => $data->short_description,
                    'description' => $data->description,
                    'video' => $data->video,                    
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
