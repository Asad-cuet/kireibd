<?php

namespace App\Http\Resources\V2;

use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BlogCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $recentPosts = [];
        $featured=Blog::where('is_active', 1)->get();

        foreach($featured as $item){
            $recentPosts[]=$this->formatBlog($item);  
        }

        return [

            
            'blogCategoryList'=>BlogCategory::where('is_active', 1)->get(),
            'recentPosts'=>$recentPosts,
            'posts' => $this->collection->map(function ($data) {
                
                return $this->formatBlog($data);;
            }),
            'totalCount'=>3,
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }

    public function formatBlog($blog)
    {
       $data= new \stdClass();

       $data->id= $blog->id;
       $data->author= "John Doe";
       $data->comments= 10;
       $data->content= $blog->description;
       $data->date= $blog->date;
       $data->slug= $blog->slug;
       $data->title= $blog->title;
       $data->type= $blog->type;
       $data->blog_categories= formatBlogCategories($blog->category, $blog->id);
       $data->video= $blog->video;
       $data->picture= formatBlogPictures($blog->banner, $blog->id);
       $data->small_picture= formatBlogPictures($blog->banner, $blog->id);

       return $data;

    }
}
