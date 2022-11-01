<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CommunityCommentCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($data) {
                return [
                    'id' => $data->id,
                    'comment' => $data->comment,
                    'customer_name' => $data->customer != null ? $data->customer->name : 'No Name',
                    'customer_avatar' => $data->customer != null ? $data->customer->avatar : '',
                    
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
