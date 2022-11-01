<?php

namespace App\Http\Resources\V2;

use App\Models\CommunityComment;
use App\Models\CommunityLike;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CommunityPostCollection extends ResourceCollection
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
                    'customer_id' => $data->customer_id,
                    'customer_name' => $data->user !=null ? $data->user->name : 'No Name',
                    'title' => $data->title,
                    'description' => $data->description,
                    'banner' => $data->banner,
                    'hashtags' => $data->banner,
                    'date'      => date('d-m-Y', strtotime($data->created_at)),
                    'is_like' => auth()->user() && CommunityLike::where(['post_id' => $data->id, 'customer_id' => auth()->user()->id])->count()>0? true: false,
                    'like_count' => CommunityLike::where(['post_id' => $data->id])->count(),
                    'comments_count' => CommunityComment::where(['post_id' => $data->id])->count(),
                    'all_comments' =>  CommunityComment::leftJoin('users','users.id','community_comments.customer_id')->select('community_comments.*', 'users.name as customer_name')->where('post_id',$data->id)->get(),
                        
                    
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
